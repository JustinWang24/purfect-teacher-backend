<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('school_id');
            $table->uuid('uuid')->comment('课程的 UUID');
            $table->string('code',30)->comment('课程编号');
            $table->string('name')->comment('课程名称');
            $table->unsignedMediumInteger('scores')->default(1)->comment('学分');
            $table->boolean('optional')->default(false)->comment('选修课还是必修课');
            $table->unsignedMediumInteger('year')->comment('年级, 比如 1 年级的数学课');
            $table->unsignedSmallInteger('term')->default(1)->comment('学期');
            $table->text('desc')->comment('课程的描述');

            $table->timestamps();
            $table->softDeletes();
        });

        // 课程的老师, 比如 数学课 可能有多个老师交
        Schema::create('course_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('school_id');
            $table->unsignedInteger('teacher_id');
            $table->string('teacher_name')->comment('老师姓名');
            $table->unsignedInteger('course_id');
            $table->string('course_code',30)->comment('课程代码');
            $table->string('course_name')->comment('课程名称');
        });

        // 课程所归属的专业, 比如 数学课 可能有多个专业都要学
        Schema::create('course_majors', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('school_id');
            $table->unsignedInteger('major_id');
            $table->string('major_name')->comment('专业名');
            $table->unsignedInteger('course_id');
            $table->string('course_code',30)->comment('课程代码');
            $table->string('course_name')->comment('课程名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_teachers');
        Schema::dropIfExists('course_majors');
    }
}
