<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateSummaryToGuestSeriesEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_series_episodes', function (Blueprint $table) {
            $table->string('date_summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_series_episodes', function (Blueprint $table) {
            $table->dropColumn('date_summary');
        });
    }
}
