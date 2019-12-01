<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('关联的学校账号');
            $table->unsignedBigInteger('user_id')->comment('关联的教师 user 账户');
            $table->unsignedMediumInteger('year')->comment('考核年度');
            $table->unsignedSmallInteger('result')->comment('考核结果');
            $table->text('comments')->nullable()->comment('考核评语');
            $table->unsignedBigInteger('approved_by')->comment('考核人');
            $table->string('title')->comment('当年的最终职称');
            $table->string('organisation_name')->comment('当年所在部门');
            $table->timestamps();
        });

        /**
         * 教师考核项配置表
         */
        Schema::create('teacher_performance_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id');
            $table->string('name',50)->comment('考核项名称');
            $table->text('description')->comment('考核项说明');
        });

        /**
         * 教师年度考核分项的表
         */
        Schema::create('teacher_performance_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('teacher_performance_id');
            $table->unsignedBigInteger('teacher_performance_config_id');
            $table->unsignedSmallInteger('result')->comment('分项考核结果');
            $table->text('comments')->comment('分项考核评语');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_performances');
        Schema::dropIfExists('teacher_performance_configs');
        Schema::dropIfExists('teacher_performance_items');
    }
}
