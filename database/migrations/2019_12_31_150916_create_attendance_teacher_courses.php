<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTeacherCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_attendance_teacher_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wifi')->nullable(false)->comment('wifi名称');
            $table->string('mac_address', 200)->nullable(false)->comment('mac_address设备标识');
            $table->dateTime('online_mine')->nullable(true)->comment('上课打卡时间');
            $table->dateTime('offline_mine')->nullable(true)->comment('下课打卡时间');
            $table->date('check_in_date')->nullable(false)->comment('打卡日期');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('1正常记录，2补卡记录');
            $table->string('notice', 200)->nullable(true)->comment('操作记录');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->unsignedInteger('grade_id')->nullable(false)->comment('班级id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('实际扫码上课的教师id');
            $table->unsignedInteger('plan_user_id')->nullable(false)->comment('计划上课的教师id');
            $table->unsignedInteger('timetable_items_id')->nullable(false)->comment('课时id');
            $table->unsignedInteger('course_id')->nullable(false)->comment('课程id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_teachers comment '教师打卡记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_attendance_teacher_courses');
    }
}
