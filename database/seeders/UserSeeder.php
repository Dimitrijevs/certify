<?php

namespace Database\Seeders;

use App\Models\User;
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
        $schools = School::with('groups')->get();

        $iterations = 3;
        $sub_iterations = 937;

        $school = $faker->randomElement($schools);
        $group = $school->groups->random();

        $password = bcrypt('Demopass!');

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@certify.com',
                'role_id' => 1,
                'country' => 'LV',
                'group_id' => null,
                'school_id' => null,
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@certify.com',
                'role_id' => 2,
                'country' => 'LV',
                'group_id' => null,
                'school_id' => null,
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teacher',
                'email' => 'teacher@certify.com',
                'role_id' => 3,
                'country' => 'LV',
                'group_id' => $group->id,
                'school_id' => $school->id,
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User Regular',
                'email' => 'user@certify.com',
                'role_id' => 4,
                'group_id' => $group->id,
                'country' => 'LV',
                'school_id' => $school->id,
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);

        $users = [];

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

        $usersCreated = 0;

        for ($j = 0; $j < $iterations; $j++) {

            for ($i = 0; $i < $sub_iterations; $i++) {

                $faker->boolean(90) ? $role = 4 : $role = 3;

                if ($role == 3) {

                    $school = $faker->randomElement($schools);
                    $group = $school->groups->random();

                } else {

                    $school = $faker->boolean(15) ? $faker->randomElement($schools) : null;
                    $group = $school?->groups->random() ?? null;

                }

                $users[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'role_id' => $role,
                    'country' => $faker->randomElement($countries),
                    'password' => $password,
                    'school_id' => $school?->id,
                    'group_id' => $group?->id,
                    'created_at' => now()->subMonth(),
                    'updated_at' => now()->subMonth(),
                ];

                $this->command->getOutput()->write("\r" . '    Users created: ' . ++$usersCreated . ' / ' . $iterations * $sub_iterations);
            }

            User::insert($users);

            $users = [];
        }

        $schools = School::all();

        foreach ($schools as $school) {
            $users = User::where('role_id', 3)
                ->where('school_id', $school->id)
                ->pluck('id')
                ->toArray();

            if ($users) {
                $school->created_by = $faker->randomElement($users);
                $school->saveQuietly();
            }
        }

        $this->command->info("\n");
    }
}
