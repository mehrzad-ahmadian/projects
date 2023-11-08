<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned()->nullable();

            $table->integer('submission_id')->unsigned();

            $table->float('automatic_total_correct')->nullable();
            $table->float('automatic_total_uncorrect')->nullable();
            $table->float('automatic_total_unanswered')->nullable();
            $table->float('automatic_score')->nullable();
            $table->float('automatic_score_by_percentage')->nullable();

            $table->text('manual_correction')->nullable();
            $table->float('manual_score')->nullable();
            $table->float('manual_score_by_percentage')->nullable();

            $table->float('total_score')->nullable();
            $table->float('total_score_by_percentage')->nullable();

            $table->timestamps();
            $table->boolean('correction_done')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('corrections');
    }
}
