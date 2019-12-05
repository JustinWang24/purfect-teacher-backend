<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendFileNameIntoActionAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_action_attachments', function (Blueprint $table) {
            $table->string('file_name')->comment('文件名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipeline_action_attachments', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
}
