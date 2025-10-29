<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('squads', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('image_path', 255)->nullable();
            $table->boolean('active')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('squads');
    }
};
