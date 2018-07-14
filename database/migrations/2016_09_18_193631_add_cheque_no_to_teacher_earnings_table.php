<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChequeNoToTeacherEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_earnings', function (Blueprint $table) {
            $table->string('cheque_no')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_earnings', function (Blueprint $table) {
            $table->dropColumn('cheque_no');
            $table->dropColumn('deleted_at');
        });
    }
}
