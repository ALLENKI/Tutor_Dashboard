<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassrooomSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_slots', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('classroom_id')->unsigned();
            $table->integer('slot_id')->unsigned();
            $table->integer('day_type_id')->unsigned();

            $table->time('start_time');
            $table->time('end_time');

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
        Schema::drop('classroom_slots');
    }
}
