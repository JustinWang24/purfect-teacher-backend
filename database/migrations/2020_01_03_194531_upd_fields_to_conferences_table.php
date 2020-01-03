<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdFieldsToConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conferences', function (Blueprint $table) {
            //
            $table->tinyInteger('type')->comment('会议类型 1:行政会议 2:职工会议 3:部门内部会议');
            $table->dropColumn('sign_out');
            $table->dropColumn('video');
        });

        Schema::table('conferences_users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->dropColumn('begin');
            $table->dropColumn('end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conferences', function (Blueprint $table) {
            //
            $table->dropColumn('type');
            $table->tinyInteger('sign_out')->default(0)->comment('是否签退 0:不需要 1:需要');
            $table->tinyInteger('video')->default(0)->comment('视频会议 0:不需要 1:需要');
        });

        Schema::table('conferences_users', function (Blueprint $table) {
            $table->integer('status')->default(0)->comment('状态 0:未签到 1:已签到 2:已签退');
            $table->dateTime('from')->comment('会议开始时间');
            $table->dateTime('to')->comment('会议结束时间');
            $table->timestamp('begin')->comment('开始签到时间')->nullable();
            $table->timestamp('end')->comment('结束签到时间')->nullable();
        });


    }
}
