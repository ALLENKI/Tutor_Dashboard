<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_earnings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('teacher_id')->unsigned()->index();
            $table->dateTime('paid_on');
            $table->double('amount', 15, 3);
            $table->string('mode')->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamps();
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->double('commission', 15, 3)->default(0);
            $table->double('teacher_amount', 15, 3)->default(0);
            $table->string('grades')->nullable();
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->double('earnings', 15, 3)->default(0);
            $table->double('payouts', 15, 3)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teacher_earnings');

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('commission');
            $table->dropColumn('teacher_amount');
            $table->dropColumn('grades');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('earnings');
            $table->dropColumn('payouts');
        });
    }
}
