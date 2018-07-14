<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsPurchasedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_purchased', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->nullable();
            $table->float('credits')->nullable();
            $table->float('price')->nullable();
            $table->string('currency_type')->nullable();
            $table->integer('bucket_id')->nullable();
            $table->integer('of_id')->nullable();
            $table->string('of_type')->nullable();
            $table->boolean('global')->default(true);
            $table->text('remarks')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('method')->nullable();
            $table->text('payment_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits_purchased');
    }
}
