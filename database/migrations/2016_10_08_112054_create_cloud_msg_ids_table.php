<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloudMsgIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloud_msg_ids', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned();

            $table->string('device_id')->nullable();

            $table->text('push_id')->nullable();

            $table->string('type')->nullable();

            $table->string('source')->nullable();
            
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
        Schema::drop('cloud_msg_ids');
    }
}
