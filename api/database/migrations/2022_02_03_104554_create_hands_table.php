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
            $table->unsignedBigInteger('first_card_id');
            $table->unsignedBigInteger('second_card_id');
            $table->unsignedBigInteger('third_card_id');
            $table->unsignedBigInteger('fourth_card_id');
            $table->unsignedBigInteger('fifth_card_id');
            $table->unsignedBigInteger('round_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('strength_id');

            $table->unique(['round_id', 'player_id']);

            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('first_card_id')->references('id')->on('cards');
            $table->foreign('second_card_id')->references('id')->on('cards');
            $table->foreign('third_card_id')->references('id')->on('cards');
            $table->foreign('fourth_card_id')->references('id')->on('cards');
            $table->foreign('fifth_card_id')->references('id')->on('cards');
            $table->foreign('strength_id')->references('id')->on('strengths');
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
