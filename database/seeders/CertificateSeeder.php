<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use App\Models\LearningTest;
use Illuminate\Database\Seeder;
use App\Models\LearningTestResult;
use Illuminate\Support\Facades\DB;
use App\Models\LearningCertificate;
use Illuminate\Support\Facades\Schema;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('learning_certificates')) {
            $certificates = [];

            $testResults = LearningTestResult::where('is_passed', true)
                ->where('finished_at', '!=', null)
                ->get();

            foreach ($testResults as $testResult) {
                $certificates[] = [
                    'user_id' => $testResult->user_id,
                    'completed_test_id' => $testResult->id,
                    'test_id' => $testResult->test_id,
                    'name' => now()->format('Y') . ' ' . $testResult->test->name,
                    'description' => "This certificate recognizes that " . User::find($testResult->user_id)->name . " has successfully completed the \"" . $testResult->test->name . "\" assessment. This achievement demonstrates proficiency in the subject matter and commitment to professional development. The skills and knowledge validated by this certificate are valuable assets in today's competitive environment.",
                    'valid_to' => now()->addYears(2)->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            LearningCertificate::insert($certificates);
        }
    }
}
