<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAttendanceLeaveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_attendance_leave_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('leave_id')->comment('请假id');
            $table->unsignedTinyInteger('teacher_attendance_id')->comment('考勤组id');
            $table->unsignedBigInteger('user_id')->comment('用户id');
            $table->unsignedTinyInteger('source')->comment('类型 1请假 2-外出 3-出差');
            $table->date('day')->comment('日期');
            $table->time('time')->comment('时间');
            $table->enum('type', ['morning', 'afternoon', 'evening'])->comment('打卡类型');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_attendance_leave_details');
    }
}
