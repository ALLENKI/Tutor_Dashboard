<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsedOnToCreditsUsedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits_used', function (Blueprint $table) {
            $table->dateTime('used_on')->nullable();
        });

        Schema::table('credits_refunds', function (Blueprint $table) {
            $table->dateTime('refunded_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits_used', function (Blueprint $table) {
            $table->dropColumn('used_on');
        });

        Schema::table('credits_refunds', function (Blueprint $table) {
            $table->dropColumn('refunded_on');
        });
    }
}
