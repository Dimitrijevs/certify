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
        Schema::create('pdf_templates', function (Blueprint $table) {
            $table->id();
            $table->string('pdf_type');
            $table->string('logo');
            $table->string('name');
            $table->string('lang');
            $table->boolean('is_default');
            $table->boolean('is_active');
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->text('source_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_details');
    }
};