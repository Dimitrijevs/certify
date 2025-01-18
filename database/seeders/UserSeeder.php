<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('lv_LV');

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

        for($i = 0; $i < 100; $i++) {
            if ($faker->boolean(85)) {
                $isStrudent = 4;
            } else {
                $isStrudent = 3;
            }

            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role_id' => $isStrudent,
                'password' => bcrypt('demopass'),
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ];
        }

        User::insert($users);
    }
}
