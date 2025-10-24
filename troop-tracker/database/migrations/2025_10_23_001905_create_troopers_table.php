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
        Schema::create('troopers', function (Blueprint $table)
        {
            $table->id('id');
            $table->integer('user_id')->default(0);
            $table->string('name');
            $table->string('email', 240)->nullable();
            $table->string('phone', 10)->nullable();
            $table->integer('squad');
            $table->integer('permissions')->default(0);
            $table->tinyInteger('spTrooper')->default(0);
            $table->tinyInteger('spCostume')->default(0);
            $table->tinyInteger('spAward')->default(0);
            $table->integer('p501')->default(0);
            $table->integer('pRebel')->default(0);
            $table->integer('pDroid')->default(0);
            $table->integer('pMando')->default(0);
            $table->integer('pOther')->default(0);
            $table->integer('pSG')->nullable()->default(0);
            $table->integer('pDE')->nullable()->default(0);
            $table->string('tkid', 20);
            $table->string('forum_id');
            $table->string('rebelforum')->nullable();
            $table->integer('mandoid')->nullable();
            $table->string('sgid', 10)->default('0');
            $table->integer('de_id')->default(0);
            $table->string('password')->nullable();
            $table->dateTime('last_active')->useCurrent();
            $table->integer('approved')->default(0);
            $table->integer('subscribe')->default(1);
            $table->integer('theme')->default(0);
            $table->integer('supporter')->default(0);
            $table->tinyInteger('esquad0')->default(1);
            $table->boolean('esquad1')->nullable()->default(true);
            $table->boolean('esquad2')->nullable()->default(true);
            $table->boolean('esquad3')->nullable()->default(true);
            $table->boolean('esquad4')->nullable()->default(true);
            $table->boolean('esquad5')->nullable()->default(true);
            $table->integer('esquad6')->default(1);
            $table->integer('esquad7')->default(1);
            $table->integer('esquad8')->default(1);
            $table->integer('esquad9')->default(1);
            $table->integer('esquad10')->default(1);
            $table->integer('esquad13')->default(1);
            $table->boolean('efast')->nullable()->default(false);
            $table->boolean('ecommandnotify')->nullable()->default(true);
            $table->boolean('econfirm')->nullable()->default(true);
            $table->string('note')->nullable();
            $table->dateTime('datecreated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('troopers');
    }
};
