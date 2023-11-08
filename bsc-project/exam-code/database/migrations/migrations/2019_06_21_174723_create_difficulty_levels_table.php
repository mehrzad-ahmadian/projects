<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDifficultyLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('difficulty_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned()->nullable();
            $table->string('name' , 256);
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
        Schema::drop('difficulty_levels');
    }
}
