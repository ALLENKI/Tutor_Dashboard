<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            
            $table->string('name');
            $table->string('username')->unique();

            $table->string('who_are_you')->default('user');
            $table->string('interested_in')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->dropColumn('username');
            $table->dropColumn('name');

            $table->dropColumn('interested_in');
            $table->dropColumn('who_are_you');
        });
    }
}
