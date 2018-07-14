<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_invitations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('class_id')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->string('status')->default('pending');

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();

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
        Schema::drop('class_invitations');
    }
}
