<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFields2ToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('mobile')->nullable();
            $table->text('interested_subjects')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('resume_file')->nullable();
            $table->string('current_profession')->nullable();
            $table->text('why_teacher')->nullable();

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
            
        });
    }
}
