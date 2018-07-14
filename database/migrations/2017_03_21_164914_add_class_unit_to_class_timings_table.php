<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassUnitToClassTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_timings', function (Blueprint $table) {
            $table->integer('class_unit_id')->unsigned()->nullable();
            $table->integer('teacher_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_timings', function (Blueprint $table) {
            $table->dropColumn('class_unit_id');
            $table->dropColumn('teacher_id');
        });
    }
}
