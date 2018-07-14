<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_units', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->integer('topic_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->integer('original_unit_id')->unsigned();

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
        Schema::drop('class_units');
    }
}
