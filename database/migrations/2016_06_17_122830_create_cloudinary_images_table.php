<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloudinaryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloudinary_images', function (Blueprint $table) 
        {
            $table->increments('id');
            
            $table->integer('of_id')->nullable();
            $table->string('of_type')->nullable();
            $table->string('type')->nullable();
            $table->integer('position')->default(0);

            $table->string('public_id');
            $table->string('format');

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
        Schema::drop('cloudinary_images');
    }
}
