<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTemplateUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_template_user', function (Blueprint $table) {
            $table->integer('coupon_template_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->primary(['coupon_template_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coupon_template_user');
    }
}
