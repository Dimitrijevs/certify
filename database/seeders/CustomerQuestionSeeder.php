<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('customer_questions')) {
            
            $users = User::where('role_id', 4)->pluck('id')->toArray();
            
            $questions = [
                [
                    'title' => 'How can I track my test progress?',
                    'description' => 'I have taken several tests but I cannot find a way to see my overall progress. Is there a dashboard or report section where I can view my test history and scores?',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => false,
                    'answered_by' => null,
                    'created_at' => now()->subDays(rand(1, 14)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
                [
                    'title' => 'How to join a school as a student?',
                    'description' => 'My company uses your platform but I can\'t figure out how to join my company\'s school. I received an invitation but don\'t know where to enter the code.',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => true,
                    'answered_by' => rand(1, 2),
                    'created_at' => now()->subDays(rand(15, 30)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
                [
                    'title' => 'Request for more language options',
                    'description' => 'I would like to take tests in English, but most of the content seems to be in Latvian. Are there plans to add more multi-language support to the platform?',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => false,
                    'answered_by' => null,
                    'created_at' => now()->subDays(rand(1, 14)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
                [
                    'title' => 'Problem with video content loading',
                    'description' => 'In the "Projektu vadības zināšanu pārbaude" course, the video content in lesson 3 doesn\'t load. I\'ve tried different browsers but still have the same issue.',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => false,
                    'answered_by' => null,
                    'created_at' => now()->subDays(rand(1, 14)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
                [
                    'title' => 'How to reset a failed test?',
                    'description' => 'I failed the "Matemātikas zināšanu pārbaude" test by a few points and would like to retake it. Is there a waiting period or can I reset it immediately?',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => false,
                    'answered_by' => null,
                    'created_at' => now()->subDays(rand(15, 30)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
                
                // Improvements
                [
                    'title' => 'Feature Request: Rainbow Mode',
                    'description' => 'It would be great to have a Rainbow mode option for the platform. This would reduce eye strain when studying at night and provide a more modern user experience.',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => false,
                    'answered_by' => null,
                    'created_at' => now()->subDays(rand(1, 14)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
                [
                    'title' => 'Suggestion: Mobile App Version',
                    'description' => 'Having a dedicated mobile app would make it much easier to study on the go. The current responsive design works, but native notifications and offline access would be valuable improvements.',
                    'user_id' => $users[array_rand($users)],
                    'is_answered' => true,
                    'answered_by' => rand(1, 2),
                    'created_at' => now()->subDays(rand(15, 30)),
                    'updated_at' => now()->subDays(rand(1, 14)),
                ],
            ];
            
            DB::table('customer_questions')->insert($questions);
        }
    }
}