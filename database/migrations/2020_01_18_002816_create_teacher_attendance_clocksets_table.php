<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAttendanceClocksetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_attendance_clocksets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_attendance_id')->comment('考勤配置 ID');
            $table->enum('week',['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])->comment('星期几');
            $table->time('start')->comment('开始时间');
            $table->time('end')->comment('截止时间');
            $table->time('morning')->comment('上午打卡');
            $table->time('morning_late')->comment('上午迟到');
            $table->time('afternoon')->nullable(true)->comment('下午打卡');
            $table->time('afternoon_late')->nullable(true)->comment('下午迟到');
            $table->time('evening')->comment('傍晚打卡');
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
        Schema::dropIfExists('teacher_attendance_clocksets');
    }
}
