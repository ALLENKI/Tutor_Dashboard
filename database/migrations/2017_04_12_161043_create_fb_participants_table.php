<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_participants', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('fb_chat_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('mute')->default(false);

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
        Schema::drop('fb_participants');
    }
}
