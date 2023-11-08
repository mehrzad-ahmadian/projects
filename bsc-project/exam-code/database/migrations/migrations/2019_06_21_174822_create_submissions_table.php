<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('exam_id')->unsigned();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->text('submissions')->nullable();

            $table->timestamps();
            $table->boolean('automatic_correction_done')->default(0);
            $table->boolean('manual_correction_done')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('submissions');
    }
}
