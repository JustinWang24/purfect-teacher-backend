<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校ID');
            $table->unsignedInteger('campus_id')->comment('校区ID');
            $table->unsignedInteger('attendance_id')->nullable(true)->index()->comment('值周任务表id');
            $table->unsignedBigInteger('user_id')->nullable(false)->index()->comment('用户id 学生老师都有可能');
            $table->date('start_time')->nullable(true)->comment('任务开始时间');
            $table->date('end_time')->nullable(true)->comment('任务截止时间');
            $table->unsignedInteger('time_slot_id')->nullable()->comment('时间槽id');
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
        Schema::dropIfExists('attendance_schedules');
    }
}
