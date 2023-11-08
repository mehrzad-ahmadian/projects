<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned()->nullable();

            $table->integer('exam_id')->unsigned();
            $table->tinyInteger('order')->unsigned()->nullable();
            $table->string('type' , 32);
            $table->string('title' , 256);
            $table->integer('difficulty_level_id')->unsigned()->nullable();

            $table->string('choice_one' , 256)->nullable();
            $table->string('choice_two' , 256)->nullable();
            $table->string('choice_three' , 256)->nullable();
            $table->string('choice_four' , 256)->nullable();
            $table->tinyInteger('multiple_choice_correct_answer')->unsigned()->nullable();

            $table->string('true_false_correct_answer' , 32)->nullable();

            $table->text('text_correct_answer')->nullable();

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
        Schema::drop('questions');
    }
}
