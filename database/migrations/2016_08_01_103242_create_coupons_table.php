<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('coupon')->unique();
            $table->dateTime('valid_from');
            $table->dateTime('valid_till')->nullable();
            $table->boolean('active')->default(false);
            $table->string('additional_type')->nullable();
            $table->double('additional_value', 15, 3)->default(0);
            $table->integer('max_usage_limit_per_user')->default(0);
            $table->integer('max_users_limit')->default(0);
            $table->integer('min_units_to_purchase')->default(0);
            $table->integer('user_id')->default(0);

            $table->enum('type', ['one-time', 'multiple','lifetime'])->default('one-time');

            $table->softDeletes();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coupons');
    }
}
