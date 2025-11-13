<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('squads', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->string('name', 64);
            $table->string('image_path_lg', 128)->nullable();
            $table->string('image_path_sm', 128)->nullable();
            $table->boolean('active')->default(false);

            // (keeping legacy support for e* columns)
            $table->string('troopers_notification_field', 32)->nullable();
            $table->integer('troop_tracker_value')->nullable();

            $table->unique(['club_id', 'name']);
        });

        Schema::create('trooper_squads', function (Blueprint $table)
        {
            $table->id();

            // Foreign keys (keeping legacy support for trooper_id)
            $table->unsignedInteger('trooper_id');
            $table->unsignedBigInteger('squad_id');

            $table->boolean('notify')->default(false);
            $table->integer('membership_status')->default(0);

            // Prevent duplicate entries
            $table->unique(['trooper_id', 'squad_id']);

            // Foreign key constraints
            $table->foreign('trooper_id')
                ->references('id')
                ->on('troopers')
                ->onDelete('cascade');
            $table->foreign('squad_id')
                ->references('id')
                ->on('squads')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trooper_squads');
        Schema::dropIfExists('squads');
    }
};
