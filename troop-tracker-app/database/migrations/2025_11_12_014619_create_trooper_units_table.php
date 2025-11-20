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
        Schema::create('tt_trooper_units', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('trooper_id')
                ->constrained('tt_troopers')
                ->cascadeOnDelete();
            $table->foreignId('unit_id')
                ->constrained('tt_units')
                ->cascadeOnDelete();

            $table->boolean('notify')->default(false);
            $table->string('status', 16)->default(MembershipStatus::None->value);

            $table->timestamps();
            $table->trooperstamps();

            // Prevent duplicate entries
            $table->unique(columns: ['trooper_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_trooper_units');
    }
};
