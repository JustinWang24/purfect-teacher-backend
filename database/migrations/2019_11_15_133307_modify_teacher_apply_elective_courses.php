<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTeacherApplyElectiveCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->text('reply_content')
                ->nullable(true)->comment('批复的内容')->change();
            $table->unsignedSmallInteger('status')->default(0)
                ->nullable(false)->comment('1 申请中、2 审核通过、 3 审核失败、 4 发布到课程表成功')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
