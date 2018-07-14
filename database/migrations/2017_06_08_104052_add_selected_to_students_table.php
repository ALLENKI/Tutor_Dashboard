<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSelectedToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('selected_days_of_week')->nullable();
            $table->string('selected_subjects')->nullable();
            $table->string('selected_times_of_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('selected_days_of_week');
            $table->dropColumn('selected_subjects');
            $table->dropColumn('selected_times_of_day');
        });
    }
}
