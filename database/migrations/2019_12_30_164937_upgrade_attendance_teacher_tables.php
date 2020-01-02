<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeAttendanceTeacherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_attendance_teacher_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false)->comment('分组名称');
            $table->time('morning_online_time')->nullable(true)->comment('规定上午上班时间');
            $table->time('morning_offline_time')->nullable(true)->comment('规定上午下班时间');
            $table->time('afternoon_online_time')->nullable(true)->comment('规定下午上班时间');
            $table->time('afternoon_offline_time')->nullable(true)->comment('规定下午下班时间');
            $table->time('night_online_time')->nullable(true)->comment('规定晚上上班时间');
            $table->time('night_offline_time')->nullable(true)->comment('规定晚上下班时间');
            $table->string('wifi_name')->nullable(true)->comment('wifi名称');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_teacher_groups comment '教师考勤分组信息表，记录标准的上下班时间和迟到等参数' ");

        Schema::create('oa_attendance_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wifi')->nullable(false)->comment('wifi名称');
            $table->string('mac_address',200)->nullable(false)->comment('mac_address设备标识');
            $table->dateTime('morning_online_mine')->nullable(true)->comment('上午上班打卡时间');
            $table->dateTime('morning_offline_mine')->nullable(true)->comment('上午下班打卡时间');
            $table->dateTime('afternoon_online_mine')->nullable(true)->comment('下午上班打卡时间');
            $table->dateTime('afternoon_offline_mine')->nullable(true)->comment('下午下班打卡时间');
            $table->dateTime('night_online_mine')->nullable(true)->comment('晚上上班打卡时间');
            $table->dateTime('night_offline_mine')->nullable(true)->comment('晚上下班打卡时间');
            $table->date('check_in_date')->nullable(false)->comment('打卡日期');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('1正常记录，2补卡记录');
            $table->string('notice',200)->nullable(true)->comment('操作记录');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('用户id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_teachers comment '教师打卡记录表' ");

        Schema::create('oa_attendance_teachers_group_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('group_id')->nullable(false)->comment('考勤组表的关联id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('考勤组成员id');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('1成员2审批人');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->string('mac_address')->nullable(true)->comment('手机识别码');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_teachers_group_members comment '考勤组的成员用户表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_attendance_teacher_groups');
        Schema::dropIfExists('oa_attendance_teachers');
        Schema::dropIfExists('oa_attendance_teachers_group_members');
    }
}
