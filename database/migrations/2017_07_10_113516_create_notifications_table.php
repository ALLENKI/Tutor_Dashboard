<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->boolean('read')->default(false);

            $table->string('hash')->nullable();

            $table->text('note')->nullable();
            $table->text('destination')->nullable();
            $table->text('payload')->nullable();
            $table->text('notify_on')->nullable();

            $table->boolean('push_android')->default(false);
            $table->boolean('push_apple')->default(false);
            $table->boolean('email')->default(false);
            $table->boolean('sms')->default(false);
            $table->boolean('sent')->default(false);

            $table->string('role')->nullable();
            
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
        Schema::drop('notifications');
    }
}
