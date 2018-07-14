<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEnrolmentUnitsNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrollment_units_new',function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('student_id');
            $table->integer('class_id');
            $table->integer('class_unit_id');
            $table->integer('classroom_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status');
            $table->float('credits_used')->nullable();
            $table->boolean('attendance')->nullable()->default(false);
            $table->integer('student_enrollment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('student_enrollment_units_new');
    }
}
