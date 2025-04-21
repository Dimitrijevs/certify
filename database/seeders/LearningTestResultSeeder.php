<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use App\Models\LearningTest;
use App\Models\LearningTestAnswer;
use App\Models\UserPurchase;
use Illuminate\Database\Seeder;
use App\Models\LearningTestResult;
use Illuminate\Support\Facades\Schema;

class LearningTestResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_test_results')) {

            // step 1: prepare uncompleted tests
            $this->seedUncompletedTests();

            // step 2: seed answers on questions
            $this->seedAnswersOnQuestions();

            // step 3: finish tests
            $this->seedCompletedTests();
        }
    }

    public function seedUncompletedTests()
    {
        $faker = Faker::create();

        $userPurchases = UserPurchase::whereNotNull('test_id')
            ->whereHas('test', function ($query) {
                $query->whereDoesntHave('details', function ($subQuery) {
                    $subQuery->where('answer_type', 'text');
                });
            })
            ->limit(2000)
            ->get();

        $results = [];

        foreach ($userPurchases as $purchase) {
            if ($faker->boolean(40)) {
                $results[] = [
                    'user_id' => $purchase->user_id,
                    'test_id' => $purchase->test_id,
                    'finished_at' => null,
                    'total_time' => null,
                    'points' => null,
                    'is_passed' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        LearningTestResult::insert($results);
    }

    public function seedAnswersOnQuestions()
    {
        $faker = Faker::create();

        $results = LearningTestResult::all();

        $testAnswers = [];

        foreach ($results as $result) {
            $test = LearningTest::find($result->test_id);

            $questions = $test->details()
                ->where('is_active', true)
                ->get();

            foreach ($questions as $question) {

                $isCorrect = $faker->boolean(75);

                if ($isCorrect) {
                    $answer = $question->answers()
                        ->where('is_correct', true)
                        ->first();
                } else {
                    $answer = $question->answers()
                        ->where('is_correct', false)
                        ->first();
                }

                $testAnswers[] = [
                    'result_id' => $result->id,
                    'test_question_id' => $question->id,
                    'user_answer' => $answer->answer,
                    'points' => $isCorrect ? $question->points : 0,
                    'question_time' => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        LearningTestAnswer::insert($testAnswers);
    }

    public function seedCompletedTests()
    {
        $testResults = LearningTestResult::all();

        foreach ($testResults as $testResult) {
            $answers = $testResult->answers;
            $totalPoints = 0;
            $totalTime = 0;

            $test = LearningTest::find($testResult->test_id);

            $pointsToPass = $test->min_score;

            foreach ($answers as $answer) {
                $totalPoints += $answer->points;
                $totalTime += $answer->question_time;
            }

            if ($totalPoints >= $pointsToPass) {
                $testResult->is_passed = true;
            } else {
                $testResult->is_passed = false;
            }

            $testResult->finished_at = now();
            $testResult->total_time = $totalTime;
            $testResult->points = $totalPoints;
            $testResult->saveQuietly();
        }
    }
}
