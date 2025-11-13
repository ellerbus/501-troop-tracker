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
        Schema::table('costumes', function (Blueprint $table)
        {
            $table->unsignedBigInteger('club_id')->nullable();

            $table->foreign('club_id')
                ->references('id')
                ->on('clubs')
                ->onDelete('set null');
        });

        Schema::table('favorite_costumes', function (Blueprint $table)
        {
            $table->foreign('trooperid')
                ->references('id')
                ->on('troopers')
                ->onDelete('cascade');

            $table->foreign('costumeid')
                ->references('id')
                ->on('costumes')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costumes', function (Blueprint $table)
        {
            $table->dropForeign(['club_id']);
            $table->dropColumn('club_id');
        });

        Schema::table('favorite_costumes', function (Blueprint $table)
        {
            $table->dropForeign(['trooperid']);
            $table->dropForeign(['costumeid']);
        });
    }
};
