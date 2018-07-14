<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStudentEnrollmentIdToStudentEnrollmentUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_enrollment_units', function (Blueprint $table) {
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
        Schema::table('student_enrollment_units', function (Blueprint $table) {
            $table->dropColumn('student_enrollment_id');
        });
    }
}
