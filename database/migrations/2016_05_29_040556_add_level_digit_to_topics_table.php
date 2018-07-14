<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLevelDigitToTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->enum('level', ['1', '2','3'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->enum('level', ['beginner', 'intermediate','advanced'])->default('beginner');
        });
    }
}
