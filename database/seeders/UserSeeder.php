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
        $schools = School::with('groups')->get();

        $iterations = 2;
        $sub_iterations = 1117;

        $school = $faker->randomElement($schools);
        $group = $school->groups->random();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@certify.com',
                'role_id' => 1,
                'country' => 'LV',
                'group_id' => null,
                'school_id' => null,
                'password' => bcrypt('demopass'),
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
                'password' => bcrypt('demopass'),
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
                'password' => bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'user@certify.com',
                'role_id' => 4,
                'group_id' => $group->id,
                'country' => 'LV',
                'school_id' => $school->id,
                'password' => bcrypt('demopass'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);

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

        // total users = 100000
        $usersCreated = 0;

        $password = bcrypt('demopass');

        for ($j = 0; $j < $iterations; $j++) {

            for ($i = 0; $i < $sub_iterations; $i++) {

                $faker->boolean(90) ? $role = 4 : $role = 3;

                $school = $faker->boolean(10) ? $faker->randomElement($schools) : null;

                $group = $school ?->groups->random() ?? null;

                $users[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'role_id' => $role,
                    'country' => $faker->randomElement($countries),
                    'password' => $password,
                    'school_id' => $school?->id,
                    'group_id' => $group?->id,
                    'created_at' => $faker->dateTimeThisYear,
                    'updated_at' => $faker->dateTimeThisYear,
                ];

                $this->command->getOutput()->write("\r" . '    Users created: ' . ++$usersCreated . ' / ' . $iterations * $sub_iterations);
            }

            User::insert($users);

            $users = [];

            $groups = Group::all();

            foreach ($groups as $group) {
                $users = User::where('role_id', 3)
                    ->where('school_id', $group->school_id)
                    ->pluck('id')
                    ->toArray();

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

        $this->command->info("\n");

        $superAdmin = User::find(1);
        $superAdmin->stripe_connect_id = 'acct_1RKRS0AcD63pWHcd';
        $superAdmin->completed_stripe_onboarding = 1;
        $superAdmin->saveQuietly();
    }
}
