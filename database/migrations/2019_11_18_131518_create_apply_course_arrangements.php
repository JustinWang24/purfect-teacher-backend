<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyCourseArrangements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_course_arrangements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('apply_id')->comment('课程的 ID');
            $table->unsignedSmallInteger('week')->comment('需要上课的周的序号');
            $table->unsignedSmallInteger('day_index')->comment('需要上课的天的序号');
            $table->unsignedSmallInteger('time_slot_id')->comment('需要上课的时间槽的序号');
            $table->unsignedInteger('building_id')->comment('教学楼id');
            $table->string('building_name',40)->comment('教学楼名称');
            $table->unsignedInteger('classroom_id')->comment('教室id');
            $table->string('classroom_name',40)->comment('教室名称');
            $table->text('teacher_notes')->nullable()->comment('授课教师或者提交的申请时的备注');
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
        Schema::dropIfExists('apply_course_arrangements');
    }
}
