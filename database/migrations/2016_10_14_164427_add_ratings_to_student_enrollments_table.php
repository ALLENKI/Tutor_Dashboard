<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatingsToStudentEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->integer('teacher_rating')->nullable();
            $table->integer('overall_rating')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('rating_given')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropColumn('teacher_rating');
            $table->dropColumn('overall_rating');
            $table->dropColumn('remarks');
            $table->dropColumn('rating_given');
        });
    }
}
