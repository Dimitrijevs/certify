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
            SchoolSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            GroupSeeder::class,
            LearningCategorySeeder::class,
            LearningResourceSeeder::class,
            PdfTemplateSeeder::class,
            LearningTestSeeder::class,
            LearningTestQuestionSeeder::class,
            LearningTestQuestionAnswerSeeder::class,
            LearningCertificationRequirementSeeder::class,
        ]);
    }
}
