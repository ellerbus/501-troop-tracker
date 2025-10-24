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
        Schema::create('uploads', function (Blueprint $table)
        {
            $table->id('id');
            $table->integer('troopid');
            $table->integer('trooperid');
            $table->string('filename')->nullable();
            $table->integer('admin')->default(0);
            $table->dateTime('date')->useCurrent();

            $table->index(['id', 'troopid', 'trooperid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
