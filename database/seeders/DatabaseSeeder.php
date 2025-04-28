<?php

namespace Database\Seeders;

use App\Models\CustomerQuestion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            GroupSeeder::class,
            UserSeeder::class,
            PdfTemplateSeeder::class,
            CustomerQuestionSeeder::class,
            CurrencySeeder::class,
            LanguageSeeder::class,
            CategorySeeder::class,
            LearningCategorySeeder::class,
            LearningResourceSeeder::class,
            LearningTestSeeder::class,
            LearningTestQuestionSeeder::class,
            LearningTestQuestionAnswerSeeder::class,
            LearningCertificationRequirementSeeder::class,
            UserPurchaseSeeder::class,
            LearningTestResultSeeder::class,
            CertificateSeeder::class,
            UserActivitySeeder::class,
        ]);
    }
}
