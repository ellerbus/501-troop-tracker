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
        Schema::create('tt_squads', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('club_id')
                ->constrained('tt_clubs')
                ->cascadeOnDelete();

            $table->string('name', 64);
            $table->string('image_path_lg', 128)->nullable();
            $table->string('image_path_sm', 128)->nullable();
            $table->boolean('active')->default(false);

            $table->timestamps();

            $table->unique(['club_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_squads');
    }
};
