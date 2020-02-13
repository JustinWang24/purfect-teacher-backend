<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMeetigSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_meeting_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meet_id')->comment('会议ID');
            $table->integer('meet_user_id')->comment('参会人员主键ID');
            $table->integer('user_id')->comment('用户ID');
            $table->string('url', 100)->comment('文件地址');
            $table->string('file_name', 50)->comment('文件名');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE new_meeting_summaries comment '会议纪要表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_meeting_summaries');
    }
}
