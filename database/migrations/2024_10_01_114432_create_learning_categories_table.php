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
        Schema::create('learning_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active');
            $table->boolean('is_public');
            $table->decimal('price', 8, 2)->nullable()->default(0);
            $table->decimal('discount', 8, 2)->nullable();
            $table->string('currency')->nullable()->default('EUR');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_categories');
    }
};
