<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('learning_certification_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // department, employee_team, employee
            $table->integer('entity_id'); // id of the entity
            $table->integer('test_id');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_certification_requirements');
    }
};
