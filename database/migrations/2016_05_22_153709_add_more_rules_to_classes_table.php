<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreRulesToClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->integer('minimum_enrollment')->nullable();
            $table->integer('maximum_enrollment')->nullable();
            $table->integer('maximum_days')->nullable();
            $table->integer('scheduling_rule_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('minimum_enrollment');
            $table->dropColumn('maximum_enrollment');
            $table->dropColumn('maximum_days');
            $table->dropColumn('scheduling_rule_id');
        });
    }
}
