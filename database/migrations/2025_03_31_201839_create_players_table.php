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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->index();
            $table->unsignedBigInteger('team_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->string('height')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->unsignedSmallInteger('jersey_number')->nullable();
            $table->string('college')->nullable();
            $table->string('country')->nullable();
            $table->unsignedSmallInteger('draft_year')->nullable();
            $table->unsignedSmallInteger('draft_round')->nullable();
            $table->unsignedSmallInteger('draft_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('team_id')->references('id')->on('teams');
            $table->unique(['external_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
