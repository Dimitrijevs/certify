<?php

namespace Database\Seeders;

use App\Models\School;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('lv_LV');

        for ($i = 0; $i < 10; $i++) {
            School::create([
                'name' => $faker->company . ' School',
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'country' => 'Latvija',
                'postal_code' => $faker->postcode,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'website' => $faker->url,
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ]);
        }
    }
}
