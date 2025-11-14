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
        Schema::create('tt_events', function (Blueprint $table)
        {
            $table->id();
            // $table->integer('thread_id')->default(0);
            // $table->integer('post_id')->default(0);
            $table->string('name', 256);
            $table->dateTime('starts_at')->nullable()->index();
            $table->dateTime('ends_at')->nullable()->index();

            // $table->string('venue')->nullable();
            // $table->string('website', 500)->nullable();
            // $table->integer('numberOfAttend')->nullable();
            // $table->integer('requestedNumber')->nullable();
            // $table->text('requestedCharacter')->nullable();
            // $table->boolean('secureChanging')->nullable();
            // $table->boolean('blasters')->nullable();
            // $table->boolean('lightsabers')->nullable();
            // $table->boolean('parking')->nullable();
            // $table->boolean('mobility')->nullable();
            // $table->text('amenities')->nullable();
            // $table->text('referred')->nullable();
            // $table->text('poc')->nullable();
            // $table->text('comments')->nullable();
            // $table->string('location', 500)->nullable();
            // $table->string('latitude')->nullable();
            // $table->string('longitude')->nullable();
            // $table->string('label', 100)->nullable();
            // $table->text('postComment')->nullable();
            // $table->text('notes')->nullable();
            $table->boolean('limit_participants')->default(true);
            $table->integer('total_troopers_allowed')->nullable();
            $table->integer('total_handlers_allowed')->nullable();
            // $table->integer('friendLimit')->default(4);
            // $table->tinyInteger('allowTentative')->default(1);
            // $table->boolean('closed')->default(false);
            // $table->integer('charityDirectFunds')->default(0);
            // $table->integer('charityIndirectFunds')->default(0);
            // $table->string('charityName')->nullable();
            // $table->integer('charityAddHours')->nullable();
            // $table->text('charityNote')->nullable();
            // $table->integer('link')->default(0)->index();
            // $table->integer('link2')->default(0)->index();

            $table->foreignId('squad_id')
                ->nullable()
                ->constrained('tt_squads')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tt_events');
    }
};