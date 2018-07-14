<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAadharCardPanCardForm16 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('Aadhar_card')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('form_16')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('Aadhar_card');
             $table->dropColumn('pan_card');
             $table->dropColumn('form_16');
        });
    }
}
