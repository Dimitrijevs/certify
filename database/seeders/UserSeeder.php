<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\School;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('lv_LV');
        $schools = School::all()->pluck('id')->toArray();

        $school = School::find($faker->randomElement($schools));
            $group = $school->groups->random();

        $users = [
            [
                'name' => 'Super Admin',
                'email'=> 'superadmin@certify.com',
                'role_id'=> 1,
                'country' => 'LV',
                'group_id'=> null,
                'school_id'=> null,
                'password'=> bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'=> 'Admin',
                'email'=> 'admin@certify.com',
                'role_id'=> 2,
                'country' => 'LV',
                'group_id'=> null,
                'school_id'=> null,
                'password'=> bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'=> 'Teacher',
                'email'=> 'teacher@certify.com',
                'role_id'=> 3,
                'country' => 'LV',
                'group_id'=> $group->id,
                'school_id'=> $school->id,
                'password'=> bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'=> 'User',
                'email'=> 'user@certify.com',
                'role_id'=> 4,
                'group_id'=> $group->id,
                'country' => 'LV',
                'school_id'=> $school->id,
                'password'=> bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Latvia
        // Lithuania
        // Estonia
        // Finland
        // Sweden
        // Norway
        // Denmark
        // Poland
        // Germany
        // Great Britain
        $countries = ['LV', 'LT', 'EE', 'FI', 'SE', 'NO', 'DK', 'PL', 'DE', 'GB'];

        for($i = 0; $i < 100; $i++) {
            if ($faker->boolean(90)) {
                $role = 4;
            } else {
                $role = 3;
            }

            if ($faker->boolean(50)) {
                $school = School::find($faker->randomElement($schools));
            } else {
                $school = null;
            }

            if ($school) {
                $group = $school->groups->random();
            } else {
                $group = null;
            }

            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role_id' => $role,
                'country' => $faker->randomElement($countries),
                'password' => bcrypt('demopass'),
                'school_id' => $school?->id,
                'group_id' => $group?->id,
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ];
        }

        User::insert($users);

        $groups = Group::all();
        $users = User::where('role_id', 3)->pluck('id')->toArray();

        foreach ($groups as $group) {
            $group->teacher_id = $faker->randomElement($users);
            $group->save();
        }

        $schools = School::all();
        foreach ($schools as $school) {
            $users = User::whereIn('role_id', [3, 2])
                ->where('school_id', $school->id)
                ->pluck('id')
                ->toArray();

            if ($users) {
                $school->created_by = $faker->randomElement($users);
                $school->save();
            }
        }
    }
}
