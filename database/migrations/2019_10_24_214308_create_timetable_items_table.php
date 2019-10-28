<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('表示是那个学校的');

            $table->unsignedMediumInteger('year')->comment('表示是哪一年的');
            $table->unsignedMediumInteger('term')->comment('表示是那个学期');

            $table->unsignedBigInteger('course_id')->comment('表示上什么课程');
            $table->unsignedBigInteger('time_slot_id')->comment('表示在那个时间段');
            $table->unsignedBigInteger('building_id')->comment('表示在那个楼上课');
            $table->unsignedBigInteger('room_id')->comment('表示在那个房间上课');
            $table->unsignedBigInteger('teacher_id')->comment('表示授课的老师');
            $table->unsignedBigInteger('grade_id')->comment('表示上课的班');
            $table->unsignedBigInteger('weekday_index')->comment('表示是星期几');
            $table->unsignedBigInteger('repeat_unit')->comment('表示重复的类型, 单周或双周等');
            $table->unsignedBigInteger('last_updated_by')->comment('表示该条记录是谁最后更新的');

            $table->dateTime('at_special_datetime')->nullable()->comment('表示这个特殊的日期要特别处理');
            $table->dateTime('to_special_datetime')->nullable()->comment('表示这个特殊的日期之前要特别处理');
            $table->unsignedBigInteger('to_replace')
                ->default(0)
                ->comment('特殊日期要替换这个字段指定的课程表项目');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetable_items');
    }
}
