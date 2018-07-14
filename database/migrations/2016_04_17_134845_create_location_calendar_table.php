<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_calendar', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('from_date');

            $table->dateTime('to_date');

            $table->integer('day_type_id')->unsigned();

            $table->integer('location_id')->unsigned();

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
        Schema::drop('location_calendar');
    }
}
