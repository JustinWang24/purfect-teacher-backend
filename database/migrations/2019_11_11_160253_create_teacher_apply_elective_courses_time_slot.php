<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherApplyElectiveCoursesTimeSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_apply_elective_courses_time_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_apply_elective_courses_id')->nullable()->comment('关联teacher_apply_elective_courses表ID');
            $table->unsignedSmallInteger('week')->nullable()->comment('需要上课的周的序号');
            $table->unsignedSmallInteger('day_index')->nullable()->comment('需要上课的天的序号');
            $table->unsignedSmallInteger('time_slot_id')->nullable()->comment('需要上课的时间槽的序号');
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
        Schema::dropIfExists('teacher_apply_elective_courses_time_slots');
    }
}
