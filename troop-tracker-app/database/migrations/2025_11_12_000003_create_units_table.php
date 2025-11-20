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
        Schema::create('tt_units', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('tt_regions')
                ->cascadeOnDelete();

            $table->string('name', 64);
            $table->boolean('active')->default(false);
            $table->string('image_path_lg', 128)->nullable();
            $table->string('image_path_sm', 128)->nullable();

            $table->timestamps();
            $table->trooperstamps();

            // Prevent duplicate entries
            $table->unique(['name']);

            $table->unique(['region_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_units');
    }
};
