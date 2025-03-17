<?php

namespace Database\Seeders;

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
            LearningCategorySeeder::class,
            LearningResourceSeeder::class,
            LearningTestSeeder::class,
            LearningTestQuestionSeeder::class,
            LearningTestQuestionAnswerSeeder::class,
            LearningCertificationRequirementSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
