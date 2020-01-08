<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeReadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_read_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('notice_id')->comment('消息ID');
            $table->integer('user_id')->comment('用户记录');
            $table->dateTime('created_at');
        });
        DB::statement(" ALTER TABLE notice_read_logs comment '通知阅读记录表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notice_read_logs');
    }
}
