<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTacherApplyElectiveCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->default(0)->comment('发布后的课程id');
            $table->string('apply_content')->comment('申请理由')->nullable();
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
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            //
            if (Schema::hasColumn('teacher_apply_elective_courses', 'apply_content')) {
                $table->dropColumn('apply_content');
            }
            if (Schema::hasColumn('teacher_apply_elective_courses', 'course_id')) {
                $table->dropColumn('course_id');
            }
        });
    }
}
