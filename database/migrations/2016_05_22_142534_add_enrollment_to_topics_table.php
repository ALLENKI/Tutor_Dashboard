<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollmentToTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->integer('minimum_enrollment')->nullable();
            $table->integer('maximum_enrollment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('minimum_enrollment');
            $table->dropColumn('maximum_enrollment');
        });
    }
}
