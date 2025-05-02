<?php

namespace Database\Seeders;

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
                'token' => 'Gy95DwxBHE6M6Mpa',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('stripe_state_tokens')->insert($token);
        }
    }
}
