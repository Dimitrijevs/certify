<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\School;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('lv_LV');
        $schools = School::all()->pluck('id')->toArray();

        $grades = range(1, 12);
        $sections = range('A', 'D');
        
        $groups = [];

        for ($i = 0; $i < 200; $i++) {
            $grade = $faker->randomElement($grades);
            $section = $faker->randomElement($sections);
            $groupName = $grade . $section;

            $groups[] = [
                'name' => $groupName,
                'description' => $faker->sentence(12),
                'school_id' => $faker->randomElement($schools),
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ];
        }

        Group::insert($groups);
    }
}
