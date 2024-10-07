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
        Schema::create('learning_test_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('result_id')->onDelete('set null');
            $table->integer('test_question_id')->onDelete('set null');
            $table->string('user_answer')->nullable();
            $table->float('points');
            $table->string('question_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_test_answers');
    }
};
