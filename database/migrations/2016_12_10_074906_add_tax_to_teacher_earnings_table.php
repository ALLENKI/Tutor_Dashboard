<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToTeacherEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_earnings', function (Blueprint $table) {
            $table->double('actual_amount', 15, 3)->nullable();
            $table->double('tax', 15, 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_earnings', function (Blueprint $table) {
            $table->dropColumn('actual_amount');
            $table->dropColumn('tax');
        });
    }
}
