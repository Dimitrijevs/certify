<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Currency;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use App\Models\LearningTest;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use App\Models\LearningCategory;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\ApiErrorException;
use Filament\Notifications\Notification;
use Illuminate\Database\DatabaseManager;

class SellerController extends Controller
{
    protected StripeClient $stripeClient;
    protected DatabaseManager $databaseManager;

    public function __construct(StripeClient $stripeClient, DatabaseManager $databaseManager)
    {
        $this->stripeClient = $stripeClient;
        $this->databaseManager = $databaseManager;
    }

    public function redirectAndNotify($errorTitle, $errorBody, $type = 'danger')
    {
        Notification::make()
                    ->title($errorTitle)
                    ->body($errorBody)
            ->{$type}()
                ->send();

        return redirect()->route('filament.app.pages.dashboard');
    }

    public function redirectToStripe($id)
    {
        $seller = User::findOrFail($id);

        if (is_null($seller)) {
            return $this->redirectAndNotify(__('seller.seller_not_found'), __('seller.seller_was_not_found'));
        }

        // complete stripe onboarding
        if (!$seller->completed_stripe_onboarding) {

            $token = Str::random();

            $this->databaseManager->table('stripe_state_tokens')->insert([
                'token' => $token,
                'seller_id' => $seller->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            try {
                if (is_null($seller->stripe_connect_id)) {

                    // Create account
                    $account = $this->stripeClient->accounts->create([
                        'country' => $seller->country,
                        'type' => 'express',
                        'email' => $seller->email,
                    ]);

                    $seller->update(['stripe_connect_id' => $account->id]);
                    $seller->fresh();
                }

                $onboardLink = $this->stripeClient->accountLinks->create([
                    'account' => $seller->stripe_connect_id,
                    'refresh_url' => route('redirect.stripe', ['id' => $seller->id]),
                    'return_url' => route('save.stripe', ['token' => $token]),
                    'type' => 'account_onboarding'
                ]);

                return redirect($onboardLink->url);

            } catch (\Exception $exception) {
                $this->redirectAndNotify(__('seller.stripe_onboarding_failed'), $exception->getMessage());
            }
        }

        try {

            $loginLink = $this->stripeClient->accounts->createLoginLink($seller->stripe_connect_id);
            return redirect($loginLink->url);

        } catch (\Exception $exception) {
            $this->redirectAndNotify(__('seller.stripe_login_failed'), $exception->getMessage());
        }
    }

    public function saveStripeAccount($token)
    {
        $stripeToken = $this->databaseManager->table('stripe_state_tokens')
            ->where('token', '=', $token)
            ->first();

        if (is_null($stripeToken)) {
            $this->redirectAndNotify(__('seller.invalid_token'), __('seller.token_is_invalid_or_expired'));
        }

        $seller = User::findOrFail($stripeToken->seller_id);

        if (is_null($seller)) {
            $this->redirectAndNotify(__('seller.seller_not_found'), __('seller.seller_was_not_found'));
        }

        $seller->update(['completed_stripe_onboarding' => true]);

        return redirect()->route('filament.app.pages.dashboard');
    }

    public function purchase($id, Request $request)
    {
        app()->setLocale(session('locale', config('app.locale')));
        
        $seller = User::findOrFail($id);

        if ($request->test_id) {
            $product = LearningTest::find($request->test_id);

            $description = __('seller.payment_for_course') . ' ' . $product->name . ' ' . __('seller.by') . ' ' . Auth::user()->name;
        } else {
            $product = LearningCategory::find($request->course_id);

            $description = __('seller.payment_for_test') . ' ' . $product->name . ' ' . __('seller.by') . ' ' . Auth::user()->name;
        }

        if (is_null($seller)) {
            $this->redirectAndNotify(__('seller.seller_not_found'), __('seller.seller_was_not_found'));
        }

        $price = $this->calculatePrice($product->price, $product->discount);

        if ($price > 0) {

            if (!$seller->completed_stripe_onboarding) {
                Notification::make()
                    ->title(Auth::user()->name . ' ' . __('seller.is_trying_to_purchase_your_product'))
                    ->body(__('seller.please_complete_stripe_onboarding_process_to_recieve_payements'))
                    ->warning()
                    ->sendToDatabase($seller);

                return $this->redirectAndNotify(__('seller.creator_does_not_have_stripe_account_yet'), __('seller.we_are_notifying_the_creator_to_complete_their_stripe_onboarding_process'));
            }

            $currency_id = $product->currency_id ?? 38;
            $currency = Currency::find($currency_id);

            try {
                $this->stripeClient->paymentIntents->create([
                    'amount' => $product->price * 100,
                    'currency' => strtolower($currency->code),
                    'payment_method_data' => [
                        'type' => 'card',
                        'card' => [
                            'token' => $request->stripeToken,
                        ],
                    ],
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],
                    'transfer_data' => [
                        'destination' => $seller->stripe_connect_id,
                        'amount' => (int) $price * 100
                    ],
                    'confirm' => true,
                    'description' => $description,
                ]);
            } catch (ApiErrorException $exception) {
                return $this->redirectAndNotify(__('seller.payment_failed'), $exception->getMessage());
            }
        }

        $userPurchase = new UserPurchase();
        $userPurchase->user_id = Auth::id();

        if ($request->test_id) {

            $userPurchase->test_id = $request->test_id;
            $body = __('seller.now_you_can_access_the_test');
        } else {

            $userPurchase->course_id = $request->course_id;
            $body = __('seller.now_you_can_access_the_course');
        }

        $userPurchase->seller_id = $seller->id;
        $userPurchase->price = $price;
        $userPurchase->currency_id = $currency_id ?? null;
        $userPurchase->save();

        $title = __('seller.your_purchase_was_successful');

        return $this->redirectAndNotify($title, $body, 'success');
    }

    public static function calculatePrice($price, $discount = null)
    {
        if ($discount) {
            $price = $price - ($price * $discount / 100);
        }

        return number_format($price, 2, '.', '');
    }
}
