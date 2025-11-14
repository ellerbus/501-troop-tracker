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
        Schema::create('tt_trooper_achievements', function (Blueprint $table)
        {
            $table->id();

            $table->foreignId('trooper_id')
                ->constrained('tt_troopers')
                ->cascadeOnDelete();

            // $table->dateTime('member_since');
            $table->integer('trooper_rank');

            // Squad completion
            $table->boolean('trooped_all_squads')->default(false);

            // First troop
            $table->boolean('first_troop_completed')->default(false);

            // Troop count milestones
            $table->boolean('trooped_10')->default(false);
            $table->boolean('trooped_25')->default(false);
            $table->boolean('trooped_50')->default(false);
            $table->boolean('trooped_75')->default(false);
            $table->boolean('trooped_100')->default(false);
            $table->boolean('trooped_150')->default(false);
            $table->boolean('trooped_200')->default(false);
            $table->boolean('trooped_250')->default(false);
            $table->boolean('trooped_300')->default(false);
            $table->boolean('trooped_400')->default(false);
            $table->boolean('trooped_500')->default(false);
            $table->boolean('trooped_501')->default(false);

            /*

            ---

            ### 🏅 Trooper Achievement Icons (Font Awesome 6.x)

            | Achievement                         | Icon Class                     | Suggested Tooltip                     |
            |-------------------------------------|--------------------------------|----------------------------------------|
            | Trooped every squad                 | `fa-solid fa-network-wired`    | "Trooped every squad in the system"    |
            | First troop completed               | `fa-solid fa-flag-checkered`   | "First troop completed"                |
            | 10 troops                           | `fa-solid fa-shield-halved`    | "10 successful missions"               |
            | 25 troops                           | `fa-solid fa-user-shield`      | "25 deployments logged"                |
            | 50 troops                           | `fa-solid fa-medal`            | "50 confirmed appearances"             |
            | 75 troops                           | `fa-solid fa-star-half-stroke` | "75 missions completed"                |
            | 100 troops                          | `fa-solid fa-star`             | "100 trooper events"                   |
            | 150 troops                          | `fa-solid fa-trophy`           | "150 events trooped"                   |
            | 200 troops                          | `fa-solid fa-trophy-star`      | "200 elite missions"                   |
            | 250 troops                          | `fa-solid fa-award`            | "250 deployments"                      |
            | 300 troops                          | `fa-solid fa-certificate`      | "300 confirmed appearances"            |
            | 400 troops                          | `fa-solid fa-crown`            | "400 legendary missions"               |
            | 500 troops                          | `fa-solid fa-gem`              | "500 elite trooper events"             |
            | 501 troops                          | `fa-solid fa-helmet-safety`    | "501st Legion Master Trooper"          |

            ---

             */

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(columns: ['trooper_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_trooper_achievements');
    }
};
