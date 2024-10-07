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
        Schema::create('learning_tests', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('sort_id');
            $table->json('category_id')->nullable()->onDelete('set null');
            $table->boolean('is_active');
            $table->string('thumbnail')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_question_transition_enabled')->default(false);
            $table->integer('min_score')->nullable();
            $table->integer('time_limit')->nullable();
            $table->integer('layout_id')->nullable();
            $table->integer('cooldown')->nullable();
            $table->string('requirement_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_tests');
    }
};
