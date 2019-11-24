<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceSchedulePersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_schedule_persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('attendance_id')->nullable(true)->index()->comment('值周任务表id');
            $table->unsignedInteger('schedule_id')->nullable(true)->index()->comment('attendance_schedule表id');
            $table->unsignedBigInteger('user_id')->nullable(false)->index()->comment('用户id 学生老师都有可能');
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
        Schema::dropIfExists('attendance_schedule_persons');
    }
}
