<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use App\Models\LearningTest;
use App\Models\UserPurchase;
use Illuminate\Database\Seeder;
use App\Models\LearningCategory;
use Illuminate\Support\Facades\Schema;

class UserPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('user_purchases')) {
            $faker = Faker::create();

            $users = User::all();
            $total_users = $users->count();
            $users_seeder = 0;

            $courses = LearningCategory::where('available_for_everyone', true)
                ->where('is_public', true)
                ->where('price', 0)
                ->get();

            $tests = LearningTest::where('available_for_everyone', true)
                ->where('is_public', true)
                ->where('price', 0)
                ->get();

            $purchases = [];

            foreach ($users as $user) {
                if ($user->id == 4) {
                    $is_purchase = true;

                } else {
                    $is_purchase = $faker->boolean(40);
                }
                
                $course_purchase = $faker->boolean();

                if ($is_purchase && $course_purchase) {
                    $course = $faker->randomElement($courses);

                    $created_at = $faker->dateTimeBetween('-1 year', 'now');
                    $updated_at = $faker->dateTimeBetween($created_at, 'now');

                    $purchases[] = [
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'test_id' => null,
                        'price' => $course->price,
                        'currency_id' => $course->currency_id,
                        'seller_id' => $course->created_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                } else if ($is_purchase && !$course_purchase) {
                    $test = $faker->randomElement($tests);

                    $created_at = $faker->dateTimeBetween('-1 year', 'now');
                    $updated_at = $faker->dateTimeBetween($created_at, 'now');

                    $purchases[] = [
                        'user_id' => $user->id,
                        'course_id' => null,
                        'test_id' => $test->id,
                        'price' => $test->price,
                        'currency_id' => $test->currency_id,
                        'seller_id' => $test->created_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                }

                if (++$users_seeder % 1000 === 0) {
                    UserPurchase::insert($purchases);
                    $purchases = [];
                }

                $this->command->getOutput()->write("\rSeeding user purchases: " . $users_seeder . '/' . $total_users);
            }
            
            UserPurchase::insert($purchases);

            $this->command->info("\n");
        }
    }
}