<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAttendanceClockinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_attendance_clockins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_attendance_id')->comment('考勤配置 ID');
            $table->unsignedBigInteger('user_id')->comment('用户 ID');
            $table->date('day')->comment('打卡日期');
            $table->time('time')->comment('打卡时间');
            $table->enum('type', ['morning', 'afternoon', 'evening'])->comment('打卡类型');
            $table->unsignedTinyInteger('status')->comment('状态 1正常 2迟到 3严重迟到 4早退');
            $table->unsignedTinyInteger('source')->comment('来源 1打卡 2补卡');
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
        Schema::dropIfExists('teacher_attendance_clockins');
    }
}
