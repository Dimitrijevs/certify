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
        Schema::create('learning_user_study_records', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->onDelete('set null');
            $table->integer('category_id')->onDelete('set null');
            $table->integer('resource_id')->onDelete('set null');
            $table->dateTime('started_at'); // when the user started studying the resource
            $table->dateTime('finished_at'); // when the user finished studying the resource
            $table->string('time_spent'); // total time spent on the resource
            $table->string('video_watched')->nullable(); // for example 20 sec of video watched
            $table->string('video_progress')->nullable(); // latest view point on the video
            $table->string('video_duration')->nullable(); // total video duration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_user_study_records');
    }
};
