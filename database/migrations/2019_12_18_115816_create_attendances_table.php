<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('timetable_id')->comment('课时ID');
            $table->unsignedInteger('course_id')->comment('课程ID');
            $table->integer('actual_number')->default(0)->comment('实到人数');
            $table->integer('leave_number')->comment('请假人数');
            $table->integer('missing_number')->comment('未到人数');
            $table->integer('total_number')->comment('总人数');
            $table->mediumInteger('year')->comment('那一年');
            $table->mediumInteger('term')->comment('学期');
            $table->unsignedBigInteger('grade_id')->comment('班级ID');
            $table->unsignedBigInteger('teacher_id')->comment('教师ID');
            $table->mediumInteger('week')->comment('当前学期的第几周');
            $table->timestamps();
        });


        Schema::create('attendances_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('attendance_id')->comment('签到ID');
            $table->unsignedInteger('course_id')->comment('课程ID');
            $table->unsignedInteger('timetable_id')->comment('课时ID');
            $table->integer('student_id')->default(0)->comment('学生ID');
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
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendances_details');
    }
}
