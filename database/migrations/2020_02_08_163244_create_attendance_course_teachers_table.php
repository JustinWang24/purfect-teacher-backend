<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceCourseTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_course_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校ID');
            $table->unsignedInteger('timetable_id')->comment('课时ID');
            $table->unsignedInteger('course_id')->comment('课程ID');
            $table->mediumInteger('year')->comment('那一年');
            $table->mediumInteger('term')->comment('学期');
            $table->unsignedBigInteger('grade_id')->comment('班级ID');
            $table->unsignedBigInteger('teacher_id')->comment('教师ID');
            $table->mediumInteger('week')->comment('当前学期的第几周');
            $table->mediumInteger('weekday_index')->comment('当周星期几');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE attendance_course_teachers comment '教师上课签到表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_course_teachers');
    }
}
