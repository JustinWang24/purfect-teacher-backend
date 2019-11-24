<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolEnrolmentStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_enrolment_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->comment('步骤名称');
            $table->integer('enrolment_step_id')->comment('系统步骤ID');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('campus_id')->comment('校区ID');
            $table->text('describe')->comment('描述')->nullable();
            $table->smallInteger('sort')->comment('序号');
            $table->integer('user_id')->comment('负责人');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE school_enrolment_steps comment '学校迎新步骤表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_enrolment_steps');
    }
}
