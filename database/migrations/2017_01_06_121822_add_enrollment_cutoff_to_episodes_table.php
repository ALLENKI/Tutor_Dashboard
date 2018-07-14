<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollmentCutoffToEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_series_episodes', function (Blueprint $table) {
            $table->dateTime('enrollment_cutoff')->nullable();
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
            $table->dropColumn('enrollment_cutoff');
        });
    }
}
