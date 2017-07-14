<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GameStepsAndPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->increments('id');
            $table->string('oplayer');
            $table->string('xplayer');
            $table->string('steps');
            $table->string('winner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game');
    }
}
