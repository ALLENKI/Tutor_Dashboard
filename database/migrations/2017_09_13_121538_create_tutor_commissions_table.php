<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutorCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutor_commissions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('teacher_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->string('value_type')->default('percent');
            $table->float('value')->default(0);
            $table->integer('min_enrollment')->default(0);
            
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
        Schema::drop('tutor_commissions');
    }
}
