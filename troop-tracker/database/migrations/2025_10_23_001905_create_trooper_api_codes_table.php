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
        Schema::create('trooper_api_codes', function (Blueprint $table)
        {
            $table->id('id');
            $table->integer('trooperid');
            $table->string('api_code', 64)->unique('api_code');
            $table->dateTime('date_created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trooper_api_codes');
    }
};
