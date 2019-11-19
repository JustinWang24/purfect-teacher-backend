<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEnrolmentStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrolment_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('school_enrolment_step_id')->comment('迎新步骤ID');
            $table->integer('school_enrolment_step_task_id')->default('0')->comment('子类步骤ID');
        });
        DB::statement(" ALTER TABLE student_enrolment_steps comment '学生迎新表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_enrolment_steps');
    }
}
