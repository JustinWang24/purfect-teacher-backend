<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTimeSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_time_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('attendance_id')->nullable(true)->index()->comment('值周任务表id');
            $table->string('title','40')->nullable(true)->comment('时段名称');
            $table->time('start_time')->nullable(true)->comment('时段开始时间');
            $table->time('end_time')->nullable(true)->comment('时段截止时间');
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
        Schema::dropIfExists('attendance_time_slots');
    }
}
