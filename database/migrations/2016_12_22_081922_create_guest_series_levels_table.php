<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestSeriesLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_series_levels', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->integer('series_id')->unsigned();
            $table->integer('order')->default(0);

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
        Schema::drop('guest_series_levels');
    }
}
