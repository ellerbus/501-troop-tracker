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
        Schema::create('clubs', function (Blueprint $table)
        {
            $table->id();
            $table->string('name', 64);
            $table->string('image_path', 128);
            $table->string('db_status_field', 32);
            $table->string('db_identifier_field', 32);
            $table->string('db_identifier_display', 64);
            $table->boolean('active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
