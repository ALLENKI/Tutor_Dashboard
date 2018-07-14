<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfTypeToClassTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_timings', function (Blueprint $table) {
            
            $table->integer('of_id')->nullable();
            $table->string('of_type')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_timings', function (Blueprint $table) {
            $table->dropColumn('of_id');
            $table->dropColumn('of_type');
        });
    }
}
