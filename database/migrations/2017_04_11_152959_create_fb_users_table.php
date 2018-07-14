<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_users', function (Blueprint $table) {
            $table->increments('id');
            
            // $table->integer('of_id')->nullable();
            // $table->string('of_type')->nullable();

            $table->integer('user_id')->unsigned();
            $table->string('email')->unique();
            $table->string('password')->nullable();


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
        Schema::drop('fb_users');
    }
}
