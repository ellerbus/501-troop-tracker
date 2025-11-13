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
        Schema::create('clubs', function (Blueprint $table)
        {
            $table->id();
            $table->string('name', 64);
            $table->string('image_path_lg', 128)->nullable();
            $table->string('image_path_sm', 128)->nullable();
            $table->string('identifier_display', 64)->nullable();
            $table->string('identifier_validation', 64)->nullable();
            $table->boolean('active')->default(false);

            // (keeping legacy support for e* columns)
            $table->string('troopers_status_field', 32)->nullable();
            $table->string('troopers_identifier_field', 32)->nullable();
            $table->string('troopers_notification_field', 32)->nullable();
            $table->integer('troop_tracker_value')->nullable();

            // Prevent duplicate entries
            $table->unique(['name']);
        });

        Schema::create('trooper_clubs', function (Blueprint $table)
        {
            $table->id();

            // Foreign keys (keeping legacy support for trooper_id)
            $table->unsignedInteger('trooper_id');
            $table->unsignedBigInteger('club_id');
            $table->string('identifier', 64);
            $table->boolean('notify')->default(false);
            $table->integer('membership_status')->default(0);

            // Prevent duplicate entries
            $table->unique(['trooper_id', 'club_id']);
            $table->unique(['club_id', 'identifier']);

            // Foreign key constraints
            $table->foreign('trooper_id')
                ->references('id')
                ->on('troopers')
                ->onDelete('cascade');
            $table->foreign('club_id')
                ->references('id')
                ->on('clubs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trooper_clubs');
        Schema::dropIfExists('clubs');
    }
};
