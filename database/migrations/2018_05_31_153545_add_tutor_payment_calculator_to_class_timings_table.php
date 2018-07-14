<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTutorPaymentCalculatorToClassTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_timings', function (Blueprint $table) {
            $table->string('tutor_payment_calculator')->default('pending');
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
            $table->dropColumn('tutor_payment_calculator');
        });
    }
}
