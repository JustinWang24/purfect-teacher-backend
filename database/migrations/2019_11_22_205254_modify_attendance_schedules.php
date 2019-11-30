<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dateTime('start_date_time')->nullable(true)->comment('任务开始时间');
            $table->dateTime('end_date_time')->nullable(true)->comment('任务截止时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->index()->comment('用户id 学生老师都有可能');
            $table->date('start_time')->nullable(true)->comment('任务开始时间');
            $table->date('end_time')->nullable(true)->comment('任务截止时间');
            $table->dropColumn('start_date_time');
            $table->dropColumn('end_date_time');
        });
    }
}
