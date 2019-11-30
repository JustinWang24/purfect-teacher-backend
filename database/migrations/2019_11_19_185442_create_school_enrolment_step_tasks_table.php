<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolEnrolmentStepTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_enrolment_step_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_enrolment_step_id')->comment('迎新步骤ID');
            $table->string('name')->comment('名称');
            $table->text('describe')->comment('描述');
            $table->tinyInteger('type')->default('0')->comment('类型 0可选 1必须执行');
        });
        DB::statement(" ALTER TABLE school_enrolment_step_tasks comment '学校迎新步骤子类表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_enrolment_step_tasks');
    }
}
