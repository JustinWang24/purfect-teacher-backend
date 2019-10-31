<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('exams_plans')) {
            Schema::create('exams_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('exam_id')->comment('考试ID');
                $table->foreign('exam_id')->references('id')->on('exams');
                $table->integer('campus_id')->comment('校区ID');
                $table->integer('institute_id')->comment('学院ID');
                $table->integer('department_id')->comment('系ID');
                $table->integer('major_id')->comment('专业ID')->nullable();
                $table->integer('grade_id')->comment('班级ID')->nullable();
                $table->tinyInteger('type')->comment('类型 1:期中 2:期末 3:随堂 4:补考 5:清考');
                $table->tinyInteger('formalism')->comment('考试形式 1:笔试 2:机试');
                $table->date('year')->comment('学年');
                $table->timestamp('from')->comment('开始时间')->nullable();
                $table->timestamp('to')->comment('结束时间')->nullable();
                $table->timestamps();
            });
        }
        DB::statement(" ALTER TABLE exams_plans comment '考试计划表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams_plans');
    }
}
