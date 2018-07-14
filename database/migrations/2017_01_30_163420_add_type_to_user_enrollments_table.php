<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToUserEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_enrollments', function (Blueprint $table) {
           $table->string('type')->default('episode'); 
           $table->string('method')->default('payment'); 
           $table->double('amount_paid', 15, 3)->default(0);
           $table->integer('credits')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_enrollments', function (Blueprint $table) {
           $table->dropColumn('type'); 
           $table->dropColumn('method'); 
           $table->dropColumn('amount_paid'); 
           $table->dropColumn('credits'); 
        });
    }
}
