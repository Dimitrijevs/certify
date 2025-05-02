<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\LearningUserStudyRecord;

class UserStudyRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_user_study_records')) {
            $users = User::all();

            $studyRecords = [];

            foreach ($users as $user) {
                $purchases = $user->purchases()->where('course_id', '!=', null)->get();

                foreach ($purchases as $purchase) {
                    $course = $purchase->course;

                    $resources = $course->learningResources()->where('is_active', true)->get();

                    foreach ($resources as $resource) {

                        for ($i = 0; $i < rand(40, 80); $i++) {

                            $startedAt = Carbon::now()->subDays(rand(1, 365))
                                ->subHours(rand(0, 23))
                                ->subMinutes(rand(0, 59))
                                ->subSeconds(rand(0, 59));

                            $completedAt = (clone $startedAt)->addSeconds(rand(5, 600));

                            $timeSpent = abs($completedAt->diffInSeconds($startedAt));

                            $studyRecords[] = [
                                'user_id' => $user->id,
                                'category_id' => $course->id,
                                'resource_id' => $resource->id,
                                'started_at' => $startedAt,
                                'finished_at' => $completedAt,
                                'time_spent' => $timeSpent,
                                'video_watched' => 0,
                                'video_progress' => 0,
                                'video_duration' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    LearningUserStudyRecord::insert($studyRecords);

                    $studyRecords = []; // Reset the array for the next user
                }
            }
        }
    }
}
