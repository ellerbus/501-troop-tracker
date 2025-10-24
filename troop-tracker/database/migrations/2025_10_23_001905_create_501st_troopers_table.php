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
        Schema::create('501st_troopers', function (Blueprint $table)
        {
            $table->id('legionid');
            $table->string('name');
            $table->string('thumbnail');
            $table->string('link');
            $table->integer('squad');
            $table->integer('approved')->default(0);
            $table->integer('status')->default(0);
            $table->integer('standing')->default(0);
            $table->dateTime('joindate')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('501st_troopers');
    }
};
