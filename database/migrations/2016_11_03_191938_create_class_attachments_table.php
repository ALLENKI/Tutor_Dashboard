<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_attachments', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('class_id')->unsigned()->index();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('identifier');
            $table->string('file_name');

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
        Schema::drop('class_attachments');
    }
}
