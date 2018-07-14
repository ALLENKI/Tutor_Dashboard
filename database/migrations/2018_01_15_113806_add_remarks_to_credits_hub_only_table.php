<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarksToCreditsHubOnlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits_hub_only', function (Blueprint $table) {
            $table->text('remarks')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits_hub_only', function (Blueprint $table) {
            $table->dropColumn('remarks');
            $table->dropColumn('invoice_no');
            $table->dropColumn('method');
        });
    }
}
