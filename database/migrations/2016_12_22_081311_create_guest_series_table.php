<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_series', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();

            $table->softDeletes();

            $table->integer('location_id')->unsigned(); //Can be Zero
            $table->integer('creator_id')->unsigned(); //Can be Zero
            $table->string('enrollment_restriction')->default('none');
            $table->string('enrollment_user')->default('public');
            $table->string('enrollment_type')->default('per_episode');

            $table->string('status')->default('initiated');
            $table->text('cancellation_reason')->nullable();

            $table->boolean('free')->default(false);

            $table->double('cost_per_episode', 15, 3)->nullable();

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
        Schema::drop('guest_series');
    }
}
