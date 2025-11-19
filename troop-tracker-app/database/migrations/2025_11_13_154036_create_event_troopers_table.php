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
        Schema::create('tt_event_troopers', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('tt_events')
                ->cascadeOnDelete();
            $table->foreignId('trooper_id')
                ->constrained('tt_troopers')
                ->cascadeOnDelete();

            $table->foreignId('club_costume_id')
                ->nullable()
                ->constrained('tt_club_costumes')
                ->cascadeOnDelete();
            $table->foreignId('backup_club_costume_id')
                ->nullable()
                ->constrained('tt_club_costumes')
                ->cascadeOnDelete();

            $table->foreignId('added_by_trooper_id')
                ->nullable()
                ->constrained('tt_troopers')
                ->cascadeOnDelete();

            $table->string('status', 16)->default('none')->index();

            // $table->string('note')->default('');

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(columns: ['event_id', 'trooper_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_event_troopers');
    }
};
