<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LearningCertificationRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if (Schema::hasTable('learning_certification_requirements')) {
        //     $LearningCertificationRequirements = [
        //         [
        //             'entity_type' => 'department',
        //             'entity_id' => 1,
        //             'test_id' => 1,
        //         ],
        //         [
        //             'entity_type' => 'department',
        //             'entity_id' => 2,
        //             'test_id' => 1,
        //         ],
        //         [
        //             'entity_type' => 'department',
        //             'entity_id' => 3,
        //             'test_id' => 2,
        //         ],
        //         [
        //             'entity_type' => 'department',
        //             'entity_id' => 4,
        //             'test_id' => 3,
        //         ],
        //         [
        //             'entity_type' => 'employee_team',
        //             'entity_id' => 1,
        //             'test_id' => 1,
        //         ],
        //         [
        //             'entity_type' => 'employee_team',
        //             'entity_id' => 1,
        //             'test_id' => 3,
        //         ],
        //         [
        //             'entity_type' => 'employee_team',
        //             'entity_id' => 2,
        //             'test_id' => 2,
        //         ],
        //         [
        //             'entity_type' => 'employee',
        //             'entity_id' => 2,
        //             'test_id' => 4,
        //         ],
        //         [
        //             'entity_type' => 'employee',
        //             'entity_id' => 1,
        //             'test_id' => 1,
        //         ],
        //         [
        //             'entity_type' => 'employee',
        //             'entity_id' => 1,
        //             'test_id' => 3,
        //         ],
        //         [
        //             'entity_type' => 'employee',
        //             'entity_id' => 2,
        //             'test_id' => 2,
        //         ],
        //         [
        //             'entity_type' => 'employee',
        //             'entity_id' => 2,
        //             'test_id' => 3,
        //         ],
        //     ];

        //     DB::table('learning_certification_requirements')->insert($LearningCertificationRequirements);
        // } else {
        //     // Handle the case where the table does not exist
        //     $this->command->info('Table "learning_certification_requirements" does not exist.');
        // }
    }
}
