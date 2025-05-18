<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StripeTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('stripe_state_tokens')) {
            $token = [
                [
                    'token' => 'Gy95DwxBHE6M6Mpa',
                    'seller_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'token' => 'LJGtK5M96CNjnSFO',
                    'seller_id' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            DB::table('stripe_state_tokens')->insert($token);

            // stripe accounts
            $superAdmin = User::find(1);
            $superAdmin->stripe_connect_id = 'acct_1RKRS0AcD63pWHcd';
            $superAdmin->completed_stripe_onboarding = 1;
            $superAdmin->saveQuietly();

            $user = User::find(4);
            $user->stripe_connect_id = 'acct_1RPr3ePC0ZpBPFbs';
            $user->completed_stripe_onboarding = 1;
            $user->saveQuietly();
        }
    }
}
