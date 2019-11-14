<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEnrolledOptionalCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrolled_optional_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('course_id')->nullable()->comment('课程的 ID');
            $table->unsignedInteger('teacher_id')->nullable()->comment('老师ID');
            $table->unsignedInteger('user_id')->nullable()->comment('学生的 ID');
            $table->unsignedSmallInteger('status')->default(0)->nullable()->comment('0 申请中、1 开班成功申请成功、 2 开班成功申请失败');
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
        Schema::dropIfExists('student_enrolled_optional_courses');
    }
}
