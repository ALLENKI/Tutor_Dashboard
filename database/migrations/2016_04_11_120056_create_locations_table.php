<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();

            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->integer('country_id')->unsigned();

            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('state_id')->unsigned();

            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();

            $table->text('street_address')->nullable();
            $table->string('landmark')->nullable();
            $table->integer('city_id')->unsigned();
            $table->integer('pincode')->unsigned();

            $table->double('latitude', 15, 3)->default(0);
            $table->double('longitude', 15, 3)->default(0);

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
        Schema::drop('locations');
        Schema::drop('countries');
        Schema::drop('states');
        Schema::drop('cities');
    }
}
