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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->index();
            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('visitor_team_id');;
            $table->date('date');
            $table->dateTime('datetime');
            $table->unsignedSmallInteger('season')->index();
            $table->string('status')->index();
            $table->unsignedSmallInteger('period');
            $table->string('time')->nullable();
            $table->boolean('postseason');
            $table->unsignedSmallInteger('home_team_score');
            $table->unsignedSmallInteger('visitor_team_score');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('visitor_team_id')->references('id')->on('teams');
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
