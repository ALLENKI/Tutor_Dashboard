<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToClassAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_attachments', function (Blueprint $table) {
            
            $table->integer('uploader_id')->unsigned();
            $table->boolean('visibility')->default(true);
            $table->string('uploader_role')->default('teacher');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_attachments', function (Blueprint $table) {
            
            $table->dropColumn('uploader_id');
            $table->dropColumn('visibility');
            $table->dropColumn('uploader_role');

        });
    }
}
