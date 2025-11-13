<?php

use App\Enums\MembershipStatus;
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
        Schema::create('tt_trooper_clubs', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('trooper_id')
                ->constrained('tt_troopers')
                ->cascadeOnDelete();
            $table->foreignId('club_id')
                ->constrained('tt_clubs')
                ->cascadeOnDelete();

            $table->string('identifier', 64);
            $table->boolean('notify')->default(false);
            $table->string('status', 16)->default(MembershipStatus::None->value);

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(columns: ['trooper_id', 'club_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_trooper_clubs');
    }
};
