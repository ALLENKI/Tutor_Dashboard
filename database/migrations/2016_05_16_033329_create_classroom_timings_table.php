<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_timings', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('class_id')->unsigned();
            $table->integer('slot_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->integer('classroom_id')->unsigned()->nullable();
            $table->dateTime('date')->nullable();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

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
        Schema::drop('class_timings');
    }
}
