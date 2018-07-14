<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyTypeToCreditsPromotionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits_promotional', function (Blueprint $table) {
            $table->string('currency_type')->nullable();
        });

        Schema::table('credits_hub_only', function (Blueprint $table) {
            $table->string('currency_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits_promotional', function (Blueprint $table) {
            $table->dropColumn('currency_type');
        });

        Schema::table('credits_hub_only', function (Blueprint $table) {
            $table->dropColumn('currency_type');
        });
    }
}
