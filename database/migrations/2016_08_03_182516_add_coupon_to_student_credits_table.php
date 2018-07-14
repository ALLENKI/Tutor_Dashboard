<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCouponToStudentCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_credits', function (Blueprint $table) {
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->double('amount_paid', 15, 3)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_credits', function (Blueprint $table) {
            $table->dropColumn('coupon_id');
            $table->dropColumn('parent_id');
            $table->dropColumn('amount_paid');
        });
    }
}
