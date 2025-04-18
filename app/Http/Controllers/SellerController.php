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
            return $this->redirectAndNotify('Seller Not Found', 'The seller was not found.');
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
                $this->redirectAndNotify('Stripe Onboarding Failed', $exception->getMessage());
            }
        }

        try {

            $loginLink = $this->stripeClient->accounts->createLoginLink($seller->stripe_connect_id);
            return redirect($loginLink->url);

        } catch (\Exception $exception) {
            $this->redirectAndNotify('Stripe Login Link Failed', $exception->getMessage());
        }
    }

    public function saveStripeAccount($token)
    {
        $stripeToken = $this->databaseManager->table('stripe_state_tokens')
            ->where('token', '=', $token)
            ->first();

        if (is_null($stripeToken)) {
            $this->redirectAndNotify('Invalid Token', 'The token is invalid or has expired.');
        }

        $seller = User::findOrFail($stripeToken->seller_id);

        if (is_null($seller)) {
            $this->redirectAndNotify('Seller Not Found', 'The seller was not found.');
        }

        $seller->update(['completed_stripe_onboarding' => true]);

        return redirect()->route('filament.app.pages.dashboard');
    }

    public function purchase($id, Request $request)
    {
        if ($request->price > 0) {
            $request->validate([
                'price' => 'required|numeric|min:0',
                'currency_id' => 'required|exists:currencies,id',
            ]);
        }

        $seller = User::findOrFail($id);

        if (is_null($seller)) {
            return $this->redirectAndNotify('Seller Not Found', 'The seller was not found.');
        }

        if ($request->price > 0) {

            if (!$seller->completed_stripe_onboarding) {
                Notification::make()
                    ->title(Auth::user()->name . ' is trying to purchase your course')
                    ->body('Please complete your Stripe onboarding process to receive payments.')
                    ->warning()
                    ->sendToDatabase($seller);

                $this->redirectAndNotify('Creator does not have a Stripe account yet', 'We are notifying the creator to complete their Stripe onboarding process.');
            }

            $currency_id = $request->currency_id ?? 38;
            $currency = Currency::find($currency_id);

            if ($request->course_id) {
                $course = LearningCategory::find($request->course_id);

                $description = 'Payment for course ' . $course->name . ' by ' . Auth::user()->name;
            } else {
                $test = LearningTest::find($request->test_id);

                $description = 'Payment for test ' . $test->name . ' by ' . Auth::user()->name;
            }

            try {
                $this->stripeClient->charges->create([
                    'amount' => $request->price * 100,
                    'currency' => strtolower($currency->code),
                    'source' => $request->stripeToken,
                    'description' => $description,
                ]);
            } catch (ApiErrorException $exception) {
                $this->redirectAndNotify('Payment Failed', $exception->getMessage());
            }

            $fee = $request->price * 100 * 0.1; // 10% fee
            $sellerMoney = $request->price * 100 - $fee; // seller gets 90%

            try {
                $this->stripeClient->transfers->create([
                    'amount' => $sellerMoney,
                    'currency' => strtolower($currency->code),
                    'destination' => $seller->stripe_connect_id,
                    'description' => $description,
                ]);
            } catch (ApiErrorException $exception) {
                $this->redirectAndNotify('Transfer Failed', $exception->getMessage());
            }

            $admins = User::where('role_id', '<', 3)->get();
            $adminFee = (int) floor($fee / count($admins));
            foreach ($admins as $admin) {
                try {
                    $this->stripeClient->transfers->create([
                        'amount' => $adminFee,
                        'currency' => strtolower($currency->code),
                        'destination' => $admin->stripe_connect_id,
                        'description' => $description,
                    ]);
                } catch (ApiErrorException $exception) {
                    Notification::make()
                        ->title('Transfer Failed')
                        ->body('Please create a Stripe account to receive payments.')
                        ->danger()
                        ->sendToDatabase($admin);
                }
            }
        }

        $userPurchase = new UserPurchase();
        $userPurchase->user_id = Auth::id();

        if ($request->course_id) {
            $userPurchase->course_id = $request->course_id;
        } else {
            $userPurchase->test_id = $request->test_id;
        }

        $userPurchase->seller_id = $seller->id;
        $userPurchase->price = $request->price;
        $userPurchase->currency_id = $currency_id ?? null;
        $userPurchase->save();

        return $this->redirectAndNotify('Your purchase was successful!', 'You now have access to this content', 'success');
    }
}
