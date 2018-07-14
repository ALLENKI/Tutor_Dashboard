<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalStudentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_student', function (Blueprint $table) {
            $table->integer('goal_id')->unsigned()->index();

            $table->integer('student_id')->unsigned()->index();

            $table->primary(['goal_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('goal_student');
    }
}
