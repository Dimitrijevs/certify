<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email'=> 'superadmin@certify.com',
                'role_id'=> 1,
                'password'=> bcrypt('demopass'),
            ],
            [
                'name'=> 'Admin',
                'email'=> 'admin@certify.com',
                'role_id'=> 2,
                'password'=> bcrypt('demopass'),
            ],
            [
                'name'=> 'Teacher',
                'email'=> 'teacher@certify.com',
                'role_id'=> 3,
                'password'=> bcrypt('demopass'),
            ],
            [
                'name'=> 'User',
                'email'=> 'user@certify.com',
                'role_id'=> 4,
                'password'=> bcrypt('demopass'),
            ],
        ];

        User::insert($users);
    }
}
