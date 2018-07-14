<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_credits', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('student_id')->unsigned();

            $table->text('remarks')->nullable();

            $table->string('method');

            $table->integer('credits');

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
        Schema::drop('student_credits');
    }
}
