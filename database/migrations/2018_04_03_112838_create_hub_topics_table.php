<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHubTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hub_topics', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('of_id')->nullable();
            $table->string('of_type')->nullable(); // Topic or Course
            $table->integer('hub_id')->nullable();

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
        Schema::drop('hub_topics');
    }
}
