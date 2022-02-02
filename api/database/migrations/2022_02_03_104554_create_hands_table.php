<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hands', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('cards');
            $table->integer('strength');
            $table->boolean('winner');
            $table->unsignedBigInteger('round_id');
            $table->unsignedBigInteger('player_id');
            $table->unique(['round_id', 'player_id']);
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('round_id')->references('id')->on('rounds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hands');
    }
}
