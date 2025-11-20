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
        Schema::create('tt_organizations', function (Blueprint $table)
        {
            $table->id();

            $table->string('name', 64);
            $table->string('slug')->unique();
            $table->string('identifier_display', 64)->nullable();
            $table->string('identifier_validation', 64)->nullable();
            $table->boolean('active')->default(false);
            $table->string('image_path_lg', 128)->nullable();
            $table->string('image_path_sm', 128)->nullable();

            $table->string('description', 512)->nullable();


            $table->timestamps();
            $table->trooperstamps();

            // Prevent duplicate entries
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_organizations');
    }
};
