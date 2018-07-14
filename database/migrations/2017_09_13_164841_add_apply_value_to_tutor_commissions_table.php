<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplyValueToTutorCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutor_commissions', function (Blueprint $table) {
            $table->string('apply_value_to')->default('per_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutor_commissions', function (Blueprint $table) {
            $table->dropColumn('apply_value_to');
        });
    }
}
