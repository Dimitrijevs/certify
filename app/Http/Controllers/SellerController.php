<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPurchase;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
        $validated = $request->validate([
            'stripeToken' => 'required|string',
        ]);

        $seller = User::findOrFail($id);

        if (is_null($seller)) {
            return $this->redirectAndNotify('Seller Not Found', 'The seller was not found.');
        }

        if (!$seller->completed_stripe_onboarding) {
            Notification::make()
                ->title(Auth::user()->name . ' is trying to purchase your course')
                ->body('Please complete your Stripe onboarding process to receive payments.')
                ->warning()
                ->sendToDatabase($seller);

            $this->redirectAndNotify('Creator does not have a Stripe account yet', 'We are notifying the creator to complete their Stripe onboarding process.');
        }

        $currency = $request->currency ?? 'EUR';

        if ($request->price > 0) {
            try {
                $this->stripeClient->charges->create([
                    'amount' => $request->price * 100,
                    'currency' => strtolower($currency),
                    'source' => $request->stripeToken,
                    'description' => 'Payment for course ' . $request->course_id . ' by ' . Auth::user()->name,
                ]);
            } catch (ApiErrorException $exception) {
                $this->redirectAndNotify('Payment Failed', $exception->getMessage());
            }

            try {
                $this->stripeClient->transfers->create([
                    'amount' => $request->price * 100,
                    'currency' => strtolower($currency),
                    'destination' => $seller->stripe_connect_id,
                    'description' => 'Payment for course ' . $request->course_id . ' by ' . Auth::user()->name,
                ]);
            } catch (ApiErrorException $exception) {
                $this->redirectAndNotify('Transfer Failed', $exception->getMessage());
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
        $userPurchase->currency = $currency;
        $userPurchase->save();

        $this->redirectAndNotify('Payment Successful', 'Your payment was successful.', 'success');
    }
}
