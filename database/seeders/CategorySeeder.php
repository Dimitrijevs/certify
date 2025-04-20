<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('categories')) {
            $jsonPath = database_path('seeders/data/categories.json');

            $json = file_get_contents($jsonPath);

            $categoriesArray = json_decode($json, true);

            $now = now()->toDateTimeString();

            foreach ($categoriesArray as &$category) {
                $category['created_at'] = $now;
                $category['updated_at'] = $now;
            }

            Category::insert($categoriesArray);
        } 
    }
}
