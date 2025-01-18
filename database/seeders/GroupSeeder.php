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
        $users = User::where('role_id', 3)->pluck('id')->toArray();

        $groups = [];

        for ($i = 0; $i < 200; $i++) {
            $groups[] = [
                'name' => $faker->company . ' Group',
                'description' => $faker->sentence(12),
                'school_id' => $faker->randomElement($schools),
                'teacher_id' => $faker->randomElement($users),
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ];
        }

        Group::insert($groups);
    }
}
