<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics_lookup', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('topic_id')->unsigned();
            $table->integer('sub_category_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('category_id')->unsigned();
            
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
        Schema::drop('topics_lookup');
    }
}
