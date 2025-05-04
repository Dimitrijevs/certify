<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\LearningResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_user_study_records')) {

            $faker = Faker::create();

            $resources = LearningResource::all();

            $users = User::all();

            $activities = [];

            foreach ($resources as $resource) {

                for ($i = 0; $i < rand(8, 20); $i++) {
                    if ($resource->video_url) {
                        continue;
                    }

                    $started_at = Carbon::instance($faker->dateTimeBetween('-1 year', 'now'));
                    $finished_at = $started_at->copy()->addSeconds(rand(10, 500));

                    // Calculate time spent in seconds
                    $diff = $finished_at->diff($started_at);
                    $time_spent = $diff->s + ($diff->i * 60) + ($diff->h * 3600);

                    $activities[] = [
                        'user_id' => $faker->randomElement($users)->id,
                        'category_id' => $resource->category_id,
                        'resource_id' => $resource->id,
                        'started_at' => $started_at,
                        'finished_at' => $finished_at,
                        'time_spent' => $time_spent,
                        'video_watched' => 0,
                        'video_progress' => 0, // end point which user watched
                        'video_duration' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            DB::table('learning_user_study_records')->insert($activities);
        }
    }
}
