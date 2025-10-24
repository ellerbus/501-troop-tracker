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
        Schema::create('event_sign_up', function (Blueprint $table)
        {
            $table->id('id');
            $table->integer('trooperid')->nullable()->index();
            $table->integer('troopid')->index();
            $table->integer('costume')->nullable();
            $table->integer('costume_backup')->default(0);
            $table->integer('status')->default(0)->index();
            $table->integer('addedby')->default(0);
            $table->string('note')->default('');
            $table->dateTime('signuptime')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sign_up');
    }
};
