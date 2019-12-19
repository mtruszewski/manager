<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTacticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tactics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->string('formation');
            $table->integer('captain');
            $table->integer('pos_1');
            $table->integer('pos_2');
            $table->integer('pos_3');
            $table->integer('pos_4');
            $table->integer('pos_5');
            $table->integer('pos_6');
            $table->integer('pos_7');
            $table->integer('pos_8');
            $table->integer('pos_9');
            $table->integer('pos_10');
            $table->integer('pos_11');
            $table->integer('pos_12');
            $table->integer('pos_13');
            $table->integer('pos_14');
            $table->integer('pos_15');
            $table->integer('pos_16');
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
        Schema::dropIfExists('user_tactics');
    }
}
