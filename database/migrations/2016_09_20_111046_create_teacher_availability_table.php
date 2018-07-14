<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_availability', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('teacher_id')->unsigned()->index();
            $table->string('day_of_the_week');
            $table->dateTime('from_date');
            $table->dateTime('to_date');

            $table->boolean('early_morning')->default(false);
            $table->boolean('morning')->default(false);
            $table->boolean('afternoon')->default(false);
            $table->boolean('evening')->default(false);
            $table->boolean('late_evening')->default(false);

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
        Schema::drop('teacher_availability');
    }
}
