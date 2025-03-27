<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');
        $tests = DB::table('learning_tests')->pluck('id');
        $certificates = [];
        $faker = Faker::create();
        $admins = DB::table('users')->where('role_id', '<', '3')->pluck('id');


        for ($i = 0; $i < 10; $i++) {
            $test_id = $tests[rand(0, count($tests) - 1)];
            $test_name = DB::table('learning_tests')->where('id', $test_id)->value('name');
            $certificates[] = [
                'user_id' => $users[rand(0, count($users) - 1)],
                'test_id' => $test_id,
                'name' => date('Y') . ' ' . $test_name . ' ' . 'Course',
                'description' => $faker->paragraph(2),
                'valid_to' => $faker->dateTimeBetween('now', '+1 year'),
                'admin_id' => $faker->randomElement($admins),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('learning_certificates')->insert($certificates);
    }
}
