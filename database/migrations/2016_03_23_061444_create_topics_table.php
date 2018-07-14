<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();

            //Type of Subject
            $table->enum('type', ['subject', 'category','sub-category','topic'])->default('topic');

            //Parent if exists any
            $table->integer('parent_id')->unsigned()->default(0);

            //If a parent has multiple children, what should be their order?
            $table->integer('order')->default(0);

            $table->integer('no_of_units')->default(0);

            $table->text('description')->nullable();

            $table->softDeletes();

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
        Schema::drop('topics');
    }
}
