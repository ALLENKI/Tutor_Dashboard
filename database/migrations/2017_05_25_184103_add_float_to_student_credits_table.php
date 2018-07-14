<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFloatToStudentCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement('ALTER TABLE student_credits MODIFY credits float(5) NOT NULL');
       DB::statement('ALTER TABLE student_enrollments MODIFY credits float(5) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::statement('ALTER TABLE student_credits MODIFY credits int NOT NULL');
       DB::statement('ALTER TABLE student_enrollments MODIFY credits int NOT NULL');

    }
}
