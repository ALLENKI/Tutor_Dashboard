<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToGuestSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_series', function (Blueprint $table) {
            $table->text('requirement')->nullable();
            $table->text('optional')->nullable();
            $table->string('series_type')->default('workshop');
            $table->text('description')->nullable();
        });

        Schema::table('guest_series_levels', function (Blueprint $table) {
            $table->text('description')->nullable();
        });

        Schema::table('guest_series_episodes', function (Blueprint $table) {
            $table->string('time_summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_series', function (Blueprint $table) {
            $table->dropColumn('requirement');
            $table->dropColumn('optional');
            $table->dropColumn('series_type');
            $table->dropColumn('description');
        });

        Schema::table('guest_series_levels', function (Blueprint $table) {
            $table->dropColumn('description');
        }); 

        Schema::table('guest_series_episodes', function (Blueprint $table) {
            $table->dropColumn('time_summary');
        });
    }
}
