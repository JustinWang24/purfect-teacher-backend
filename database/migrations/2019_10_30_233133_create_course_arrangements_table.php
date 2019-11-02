<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseArrangementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_arrangements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('course_id')->comment('课程的 ID');
            $table->unsignedSmallInteger('week')->comment('需要上课的周的序号');
            $table->unsignedSmallInteger('day_index')->comment('需要上课的天的序号');
            $table->unsignedSmallInteger('time_slot_id')->comment('需要上课的时间槽的序号');
        });

        Schema::table('course_teachers', function (Blueprint $table) {
            $table->text('teacher_notes')->nullable()
                ->comment('授课教师或者提交的申请时的备注');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_arrangements');
        Schema::table('course_teachers', function (Blueprint $table) {
            $table->dropColumn('teacher_notes');
        });
    }
}
