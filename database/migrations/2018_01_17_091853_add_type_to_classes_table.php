<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('type')->default('single_class'); // Other option group_class
            $table->integer('group_class_id')->default(0);
            $table->string('enrollment_type')->default('all'); // Other options: only_one, any
            $table->integer('of_id')->nullable();
            $table->string('of_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('type');
            $table->dropColumn('group_class_id');
            $table->dropColumn('enrollment_type');
            $table->dropColumn('of_id');
            $table->dropColumn('of_type');
        });
    }
}
