<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestSeriesEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_series_episodes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('series_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('topic_id')->unsigned();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();

            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->integer('repeat_of')->default(0);

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
        Schema::drop('guest_series_episodes');
    }
}
