<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherApplyElectiveCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->nullable()->comment('学校ID');
            $table->unsignedInteger('teacher_id')->nullable()->comment('老师ID');
            $table->string('teacher_name',255)->nullable()->comment('老师姓名');
            $table->unsignedInteger('major_id')->nullable()->comment('选修课程所属的专业');
            $table->string('code',30)->nullable()->comment('选修课程编号');
            $table->string('name',255)->nullable()->comment('选修课程名称');
            $table->unsignedMediumInteger('scores')->default(1)->nullable()->comment('选修课程学分');
            $table->unsignedMediumInteger('year')->nullable()->comment('年级, 比如 1 年级的数学课');
            $table->unsignedSmallInteger('term')->default(1)->nullable()->comment('学期');
            $table->text('desc')->comment('课程的描述');
            $table->unsignedSmallInteger('open_num')->default(1)->nullable()->comment('开班人数设置');
            $table->unsignedSmallInteger('status')->default(0)->nullable()->comment('0 申请中、1 审核通过、 2 审核失败、 3 开班成功');
            $table->text('reply_content')->comment('批复的内容');
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
        Schema::dropIfExists('teacher_apply_elective_courses');
    }
}
