<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollmentLimitToGuestSeriesEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_series_episodes', function (Blueprint $table) {
           $table->integer('enrollment_limit')->default(0); 
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
            $table->dropColumn('enrollment_limit');
        });
    }
}
