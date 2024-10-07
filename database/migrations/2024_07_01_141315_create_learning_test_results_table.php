<?php

namespace Vendemy\Learning\Database\Migrations;

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
        Schema::create('learning_test_results', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->onDelete('set null');
            $table->integer('test_id')->onDelete('set null');
            $table->dateTime('finished_at')->nullable();
            $table->integer('total_time')->nullable();
            $table->float('points')->nullable();
            $table->boolean('is_passed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_test_results');
    }
};
