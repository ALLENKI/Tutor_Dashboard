<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalTopicPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_topic', function (Blueprint $table) {
            $table->integer('goal_id')->unsigned()->index();
            $table->integer('topic_id')->unsigned()->index();
            $table->primary(['goal_id', 'topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('goal_topic');
    }
}
