<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_applicants', function (Blueprint $table) {
            $table->increments('id');

            $table->string('full_name')->nullable();
            $table->string('age')->nullable();
            $table->string('school')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->text('other_programs')->nullable();
            $table->text('programming_exp')->nullable();
            $table->text('business_vertical')->nullable();
            $table->text('summer_exp')->nullable();
            $table->text('fav_books')->nullable();
            $table->text('challenge')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bet_applicants');
    }
}
