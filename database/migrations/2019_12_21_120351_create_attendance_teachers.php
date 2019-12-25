<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wifi')->nullable(false)->comment('wifi名称');
            $table->string('mac_address',200)->nullable(false)->comment('mac_address设备标识');
            $table->dateTime('online_mine')->nullable(true)->comment('上班打卡时间');
            $table->dateTime('offline_mine')->nullable(true)->comment('下班打卡时间');
            $table->date('check_in_date')->nullable(false)->comment('打卡日期');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('1正常记录，2补卡记录');
            $table->string('notice',200)->nullable(true)->comment('操作记录');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE attendance_teachers comment '教师打卡记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_teachers');
    }
}
