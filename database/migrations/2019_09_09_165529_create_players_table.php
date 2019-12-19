<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('team_id')->unsigned();
            $table->string('name');
            $table->string('surname');
            $table->integer('number');
            $table->date('age');
            $table->integer('height');
            $table->integer('weight');
            $table->string('foot');
            $table->decimal('speed', 4, 2);
            $table->decimal('stamina', 4, 2);
            $table->decimal('intelligence', 4, 2);
            $table->decimal('short_pass', 4, 2);
            $table->decimal('long_pass', 4, 2);
            $table->decimal('ball_control', 4, 2);
            $table->decimal('heading', 4, 2);
            $table->decimal('shooting', 4, 2);
            $table->decimal('tackling', 4, 2);
            $table->decimal('set_plays', 4, 2);
            $table->decimal('keeping', 4, 2);
            $table->decimal('experience', 4, 2);
            $table->decimal('form', 4, 2);
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
        Schema::dropIfExists('players');
    }
}
