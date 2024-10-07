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
        Schema::create('learning_test_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('test_id')->onDelete('set null');
            $table->boolean('is_active');
            $table->string('question_title');
            $table->text('question_description')->nullable();
            $table->string('answer_type');  // 'text', 'select_option'
            $table->smallInteger('points');
            $table->string('visual_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_test_questions');
    }
};
