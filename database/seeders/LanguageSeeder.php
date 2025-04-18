<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Schema::hasTable('languages')) {
            $jsonPath = database_path('seeders/data/languages.json');

            // Read the JSON file
            $json = file_get_contents($jsonPath);

            // Decode the JSON data into an array
            $languagesArray = json_decode($json, true);
            
            // Add timestamps to each record
            $now = Carbon::now()->toDateTimeString();
            
            foreach ($languagesArray as &$language) {
                $language['created_at'] = $now;
                $language['updated_at'] = $now;
            }

            Language::insert($languagesArray);
        }
    }
}
