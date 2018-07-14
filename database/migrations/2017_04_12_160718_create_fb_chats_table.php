<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_chats', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('of_id')->nullable();
            $table->string('of_type')->nullable();
            $table->string('name')->nullable();
            $table->string('thread')->unique();
            $table->boolean('active')->default(true);
            $table->string('type')->default('class');
            $table->integer('location_id')->unsigned();


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
        Schema::drop('fb_chats');
    }
}
