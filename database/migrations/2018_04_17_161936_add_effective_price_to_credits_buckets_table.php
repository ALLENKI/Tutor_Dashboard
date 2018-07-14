<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEffectivePriceToCreditsBucketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits_buckets', function (Blueprint $table) {
            $table->float('total_price')->default(0);
            $table->float('price_per_credit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits_buckets', function (Blueprint $table) {
            $table->dropColumn('total_price');
            $table->dropColumn('price_per_credit');
        });
    }
}
