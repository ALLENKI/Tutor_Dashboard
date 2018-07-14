<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToClassInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_invitations', function (Blueprint $table) {
            $table->string('decline_reason')->nullable();
            $table->text('decline_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_invitations', function (Blueprint $table) {
            $table->dropColumn('decline_reason');
            $table->dropColumn('decline_remarks');
        });
    }
}
