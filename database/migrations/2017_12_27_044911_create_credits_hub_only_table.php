<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsHubOnlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_hub_only', function (Blueprint $table) {
                        $table->increments('id');
                        $table->timestamps();
                        $table->integer('user_id')->nullable();
                        $table->integer('bucket_id')->nullable();
                        $table->float('credits')->nullable();
                        $table->float('price')->nullable();
                        $table->string('of_type')->nullable();
                        $table->integer('of_id')->nullable();
                });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits_hub_only');
    }
}
