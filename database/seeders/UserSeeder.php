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
        $groups = Group::all()->pluck('id')->toArray();
        $schools = School::all()->pluck('id')->toArray();

        $users = [
            [
                'name' => 'Super Admin',
                'email'=> 'superadmin@certify.com',
                'role_id'=> 1,
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
                'group_id'=> null,
                'school_id'=> 1,
                'password'=> bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'=> 'User',
                'email'=> 'user@certify.com',
                'role_id'=> 4,
                'group_id'=> 1,
                'school_id'=> 1,
                'password'=> bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        for($i = 0; $i < 200; $i++) {
            if ($faker->boolean(90)) {
                $isStrudent = 4;
            } else {
                $isStrudent = 3;
            }

            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role_id' => $isStrudent,
                'password' => bcrypt('demopass'),
                'group_id' => $faker->randomElement($groups),
                'school_id' => $faker->randomElement($schools),
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
    }
}
