<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCourseElectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_electives', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(0)->comment('选修课状态:0: 等待, 1: 成功开课, 2: 被管理员取消');
            $table->unsignedSmallInteger('max_num')->default(1)->nullable()->comment('最大人数');
            $table->timestamp('enrol_start_at')->nullable()->comment('报名开始时间');
            $table->timestamp('expired_at')->nullable()->comment('录取截止时间');
            $table->text('note')->nullable()->comment('为啥把我取消');
        });

        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->renameColumn('min_applied','max_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_electives', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('max_num');
            $table->dropColumn('enrol_start_at');
            $table->dropColumn('expired_at');
            $table->dropColumn('note');
        });

        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->renameColumn('max_num','min_applied');
        });
    }
}
