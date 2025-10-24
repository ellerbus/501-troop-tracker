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
        Schema::create('501st_costumes', function (Blueprint $table) {
            $table->integer('legionid');
            $table->integer('costumeid');
            $table->string('prefix', 2);
            $table->string('costumename');
            $table->string('photo');
            $table->string('thumbnail');
            $table->string('bucketoff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('501st_costumes');
    }
};
