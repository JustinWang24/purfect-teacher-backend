<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTeacherGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_teacher_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false)->comment('分组名称');
            $table->time('online_time')->nullable(false)->comment('规定上班时间');
            $table->time('offline_time')->nullable(false)->comment('规定下班时间');
            $table->unsignedInteger('late_duration')->nullable(false)->default(10)->comment('迟到时长');
            $table->unsignedInteger('serious_late_duration')->nullable(false)->default(120)->comment('严重迟到时长');
            $table->string('wifi_name')->nullable(true)->comment('wifi名称');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE attendance_teacher_groups comment '教师考勤分组信息表，记录标准的上下班时间和迟到等参数' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_teacher_groups');
    }
}
