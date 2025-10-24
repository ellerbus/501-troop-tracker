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
        Schema::create('notification_check', function (Blueprint $table) {
            $table->integer('troopid')->default(0);
            $table->integer('trooperid')->default(0);
            $table->integer('commentid')->default(0);
            $table->integer('trooperstatus')->default(0);
            $table->integer('troopstatus')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_check');
    }
};
