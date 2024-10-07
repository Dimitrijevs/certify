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
        Schema::create('learning_certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->onDelete('set null');
            $table->integer('completed_test_id')->nullable()->onDelete('set null');
            $table->integer('test_id')->nullable()->onDelete('set null');
            $table->integer('email_template_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('valid_to')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('admin_id')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_certificates');
    }
};
