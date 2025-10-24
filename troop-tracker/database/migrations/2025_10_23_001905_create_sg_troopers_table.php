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
        Schema::create('sg_troopers', function (Blueprint $table) {
            $table->string('sgid');
            $table->string('name');
            $table->string('image');
            $table->string('link');
            $table->string('costumename', 100);
            $table->string('ranktitle', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sg_troopers');
    }
};
