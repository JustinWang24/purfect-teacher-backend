<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMeetingFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_meeting_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meet_id')->comment('会议ID');
            $table->string('url',100)->comment('会议ID');
            $table->string('file_name',50)->comment('文件名');
        });
        DB::statement(" ALTER TABLE new_meeting_files comment '会议文件' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_meeting_files');
    }
}
