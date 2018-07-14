<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddedOnToCreditsPurchasedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits_purchased', function (Blueprint $table) {
            $table->dateTime('added_on')->nullable();
        });

        Schema::table('credits_promotional', function (Blueprint $table) {
            $table->dateTime('added_on')->nullable();
        });

        Schema::table('credits_hub_only', function (Blueprint $table) {
            $table->dateTime('added_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits_purchased', function (Blueprint $table) {
            $table->dropColumn('added_on');
        });

        Schema::table('credits_promotional', function (Blueprint $table) {
            $table->dropColumn('added_on');
        });

        Schema::table('credits_hub_only', function (Blueprint $table) {
            $table->dropColumn('added_on');
        });
    }
}
