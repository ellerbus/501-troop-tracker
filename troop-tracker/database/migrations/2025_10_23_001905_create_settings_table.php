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
        Schema::create('settings', function (Blueprint $table) {
            $table->integer('lastidtrooper')->default(0);
            $table->integer('lastidevent')->default(0);
            $table->integer('lastidlink')->default(0);
            $table->integer('siteclosed')->default(0);
            $table->integer('signupclosed')->default(0);
            $table->integer('lastnotification')->default(0);
            $table->integer('supportgoal')->default(0);
            $table->integer('notifyevent')->default(0);
            $table->dateTime('syncdate')->useCurrent();
            $table->dateTime('syncdaterebels')->useCurrent();
            $table->text('sitemessage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
