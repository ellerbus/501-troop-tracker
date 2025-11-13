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
        Schema::create('tt_clubs', function (Blueprint $table)
        {
            $table->id();

            $table->string('name', 64);
            $table->string('image_path_lg', 128)->nullable();
            $table->string('image_path_sm', 128)->nullable();
            $table->string('identifier_display', 64)->nullable();
            $table->string('identifier_validation', 64)->nullable();
            $table->boolean('active')->default(false);

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_clubs');
    }
};
