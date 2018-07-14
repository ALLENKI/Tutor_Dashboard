<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutorPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tutor_payments', function (Blueprint $table) {
                        $table->increments('id');
                        $table->integer('hub_id');
                        $table->integer('tutor_id');
                        $table->text('timings');
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
        Schema::dropIfExists('tutor_payments');
    }
}
