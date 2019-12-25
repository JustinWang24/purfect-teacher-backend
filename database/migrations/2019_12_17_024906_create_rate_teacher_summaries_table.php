<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateTeacherSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 教师授课情况统计年度总表
         */
        Schema::create('rate_teacher_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('year')->comment('学年');
            $table->unsignedInteger('term')->comment('学期');
            $table->unsignedBigInteger('teacher_id')->comment('教师');
            $table->unsignedInteger('total_points')->default(0)->comment('总分');
            $table->float('average_points')->default(0)->comment('平均分');
            $table->unsignedSmallInteger('total_rates')->default(0)->comment('总评价次数');
            $table->unsignedSmallInteger('prepare')->default(0)->comment('准备情况');
            $table->unsignedSmallInteger('material')->default(0)->comment('学习材料');
            $table->unsignedSmallInteger('on_time')->default(0)->comment('准时');
            $table->unsignedSmallInteger('positive')->default(0)->comment('生动有趣');
            $table->unsignedSmallInteger('result')->default(0)->comment('授课结果是否有用');
            $table->unsignedSmallInteger('other')->default(0)->comment('其他, 预留字段');
            $table->timestamps();
        });

        Schema::create('rate_teacher_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('year')->comment('学年');
            $table->unsignedInteger('term')->comment('学期');
            $table->unsignedBigInteger('teacher_id')->comment('教师');
            $table->unsignedBigInteger('student_id')->comment('打分的学生');
            $table->unsignedBigInteger('course_id')->comment('关联的课程');
            $table->unsignedBigInteger('timetable_item_id')->comment('课程表项');
            $table->unsignedSmallInteger('calendar_week_number')->comment('第几周');

            $table->float('average_points')->default(0)->comment('平均分');
            $table->unsignedSmallInteger('prepare')->default(0)->comment('准备情况');
            $table->unsignedSmallInteger('material')->default(0)->comment('学习材料');
            $table->unsignedSmallInteger('on_time')->default(0)->comment('准时');
            $table->unsignedSmallInteger('positive')->default(0)->comment('生动有趣');
            $table->unsignedSmallInteger('result')->default(0)->comment('授课结果是否有用');
            $table->unsignedSmallInteger('other')->default(0)->comment('其他, 预留字段');
            $table->text('comment')->nullable()->comment('评语');
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
        Schema::dropIfExists('rate_teacher_summaries');
        Schema::dropIfExists('rate_teacher_details');
    }
}
