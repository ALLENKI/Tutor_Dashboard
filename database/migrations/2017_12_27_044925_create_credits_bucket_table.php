<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsBucketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_buckets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->nullable();
            $table->float('purchased_total')->nullable();
            $table->float('promotional_total')->nullable();
            $table->float('hub_only_total')->nullable();
            $table->float('total_credits')->nullable();
            $table->float('purchased_remaining')->nullable();
            $table->float('promotional_remaining')->nullable();
            $table->float('hub_only_remaining')->nullable();
            $table->float('total_remaining')->nullable();
            $table->integer('hub_id')->nullable();
            $table->string('hub_credits_type')->nullable()->default('global');
            $table->string('currency_type')->nullable();
            $table->integer('priority')->nullable();
            $table->boolean('global')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits_buckets');
    }
}
