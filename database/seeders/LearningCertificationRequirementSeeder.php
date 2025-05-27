<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\LearningTest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LearningCertificationRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $groups = Group::all();
        $tests = LearningTest::all();

        foreach ($groups as $group) {
            $LearningCertificationRequirements[] = [
                'entity_type' => 'group',
                'entity_id' => $group->id,
                'test_id' => $faker->randomElement($tests)->id,
                'school_id' => $group->school_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('learning_certification_requirements')->insert($LearningCertificationRequirements);
    }
}
