<?php

namespace App\Http\Controllers;

use App\Models\User;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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

    public function redirectToStripe($id)
    {
        $seller = User::findOrFail($id);

        if (is_null($seller)) {
            abort(404);
        }

        // complete stripe onboarding
        if (!$seller->completed_stripe_onboarding) {
            $token = Str::random(60);
            $this->databaseManager->table('stripe_state_tokens')->insert([
                'token' => $token,
                'seller_id' => $seller->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (is_null($seller->stripe_connect_id)) {
                $account = $this->stripeClient->accounts->create([
                    'country' => 'LV',
                    'type' => 'express',
                    'email' => $seller->email,
                ]);
    
                $seller->update(['stripe_connect_id' => $account->id]);
                $seller->fresh();
            }
    
            $onboardingLink = $this->stripeClient->accountLinks->create([
                'account' => $seller->stripe_connect_id,
                'refresh_url' => route('redirect.stripe', $seller->id),
                'return_url' => route('save.stripe', $token),
                'type' => 'account_onboarding',
            ]);
    
            return redirect($onboardingLink->url);
        }

        $loginLink = $this->stripeClient->accounts->createLoginLink($seller->stripe_connect_id);
        return redirect($loginLink->url);
    }

    public function saveStripeAccount($token)
    {
        $stripeToken = $this->databaseManager->table('stripe_state_tokens')
            ->where('token', $token)
            ->first();

        if (is_null($stripeToken)) {
            abort(404);
        }

        $seller = User::findOrFail($stripeToken->seller_id);

        if (is_null($seller)) {
            abort(404);
        }

       $seller->update(['completed_stripe_onboarding' => true]);

       return redirect()->route('filament.app.pages.dashboard');
    }
}
