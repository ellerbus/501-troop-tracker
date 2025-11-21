<?php

use App\Enums\MembershipRole;
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
        Schema::create('tt_trooper_regions', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('trooper_id')
                ->constrained('tt_troopers')
                ->cascadeOnDelete();
            $table->foreignId('region_id')
                ->constrained('tt_regions')
                ->cascadeOnDelete();

            $table->boolean('notify')->default(false);
            $table->string('membership_status', 16)->default(MembershipStatus::None->value);
            $table->string('membership_role', 16)->default(MembershipRole::Member->value);

            $table->timestamps();
            $table->trooperstamps();

            // Prevent duplicate entries
            $table->unique(columns: ['trooper_id', 'region_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_trooper_regions');
    }
};
