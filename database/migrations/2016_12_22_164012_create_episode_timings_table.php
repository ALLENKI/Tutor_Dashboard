<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodeTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_timings', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('series_id')->unsigned();
            $table->integer('slot_id')->unsigned();
            $table->integer('episode_id')->unsigned();
            $table->integer('classroom_id')->unsigned()->nullable();
            $table->dateTime('date')->nullable();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->integer('location_id')->unsigned();
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending');

            $table->softDeletes();

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
        Schema::drop('episode_timings');
    }
}
