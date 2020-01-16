<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemNotificationsReadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_notifications_read_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('system_notifications_maxid')->comment('已读的最大消息ID');
            $table->integer('user_id')->unique()->comment('用户id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE system_notifications_read_logs comment '系统消息已读记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_notifications_read_logs');
    }
}
