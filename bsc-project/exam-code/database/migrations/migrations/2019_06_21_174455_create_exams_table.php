<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned()->nullable();

            $table->string('name' , 256);
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('total_score')->unsigned()->nullable();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('duration')->unsigned()->nullable();

            $table->timestamps();
            $table->boolean('enabled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exams');
    }
}
