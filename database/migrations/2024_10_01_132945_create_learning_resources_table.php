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
        Schema::create('learning_resources', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('sort_id');
            $table->integer('category_id')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active');
            $table->json('gallery')->nullable();
            $table->json('file_upload')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_resources');
    }
};
