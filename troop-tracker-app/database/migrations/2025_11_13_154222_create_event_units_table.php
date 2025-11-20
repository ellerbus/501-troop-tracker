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
        Schema::create('tt_event_units', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('tt_events')
                ->cascadeOnDelete();
            $table->foreignId('unit_id')
                ->constrained('tt_units')
                ->cascadeOnDelete();

            $table->integer('troopers_allowed')->default(500);
            $table->integer('handlers_allowed')->default(500);

            $table->timestamps();
            $table->trooperstamps();

            // Prevent duplicate entries
            $table->unique(columns: ['event_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_event_units');
    }
};
