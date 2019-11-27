<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceSchedulesAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->index()->comment('用户id 学生老师都有可能');
            $table->unsignedBigInteger('week')->nullable(false)->comment('星期，0代表周日');
            $table->unique(['time_slot_id', 'user_id'], 'idx_time_slot_user');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
        Schema::dropIfExists('attendance_schedule_persons');
        Schema::dropIfExists('attendance_persons');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('week');
            $table->date('start_time')->nullable(true)->comment('任务开始时间');
            $table->date('end_time')->nullable(true)->comment('任务截止时间');
            $table->dropUnique('idx_time_slot_user');
        });
    }
}
