<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('currencies')) {
            // Path to the JSON file
            $jsonPath = database_path('seeders/data/currencies.json');

            // Read the JSON file
            $json = file_get_contents($jsonPath);

            // Decode the JSON data into an array
            $currenciesArray = json_decode($json, true);
            
            // Add timestamps to each record
            $now = Carbon::now()->toDateTimeString();
            
            foreach ($currenciesArray as &$currency) {
                $currency['created_at'] = $now;
                $currency['updated_at'] = $now;
            }

            DB::table('currencies')->insert($currenciesArray);
        }
    }
}
