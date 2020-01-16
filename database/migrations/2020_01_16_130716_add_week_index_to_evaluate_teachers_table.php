<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeekIndexToEvaluateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluate_teachers', function (Blueprint $table) {
            //
            $table->smallInteger('week')->comment('学周');
            $table->tinyInteger('weekday_index')->comment('周几');
            $table->smallInteger('time_slot_id')->comment('第几节课');
        });
        Schema::table('evaluate_students', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('type');
            $table->dropColumn('status');
            $table->dropColumn('desc');
        });
        Schema::table('evaluate_teacher_records', function (Blueprint $table) {
            $table->integer('evaluate_student_id')->comment('学生评教主表ID');
            $table->dropColumn('evaluate_teacher_id');
            $table->dropColumn('grade_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluate_teachers', function (Blueprint $table) {
            //
            $table->dropColumn('week');
            $table->dropColumn('weekday_index');
            $table->dropColumn('time_slot_id');
        });

        Schema::table('evaluate_students', function (Blueprint $table) {
            $table->smallInteger('year')->comment('学年');
            $table->tinyInteger('type')->comment('学期');
            $table->tinyInteger('status')->comment('是否评教 0:未评教 1:已评教');
            $table->string('desc')->comment('描述');
        });
        Schema::table('evaluate_teacher_records', function (Blueprint $table) {
            $table->dropColumn('evaluate_student_id');
            $table->integer('evaluate_teacher_id')->comment('评教ID');
            $table->integer('grade_id')->comment('班级ID');
        });

    }
}
