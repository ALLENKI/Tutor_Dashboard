<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_otps', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('mobile')->unique();
            $table->string('otp')->nullable();
            $table->timestamp('expires_on');
            
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
        Schema::drop('mobile_otps');
    }
}
