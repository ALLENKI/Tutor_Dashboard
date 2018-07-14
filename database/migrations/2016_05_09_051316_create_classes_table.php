<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code')->unique();

            $table->integer('topic_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('creator_id')->unsigned();

            $table->integer('teacher_id')->unsigned()->nullable();
            $table->integer('classroom_id')->unsigned()->nullable();

            $table->boolean('scheduled')->default(false);

            $table->integer('size')->default(0);

            $table->string('status')->default('proposed');

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
        Schema::drop('classes');
    }
}
