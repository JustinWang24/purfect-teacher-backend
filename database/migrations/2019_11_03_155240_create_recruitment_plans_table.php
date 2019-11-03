<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('major_id');
            $table->unsignedBigInteger('manager_id')->default(0)->comment('招生负责人');
            $table->boolean('expired')->default(false)->comment('是否强制结束招生');
            $table->boolean('hot')->default(false)->comment('是否热门专业');
            $table->unsignedTinyInteger('type')->default(0)->comment('招生类型: 自主招生/统招');

            $table->string('major_name')->comment('专业名');
            $table->string('title')->comment('本次招生计划的标题');
            $table->date('start_at')->comment('开始招生日期');
            $table->date('end_at')->nullable()->comment('开始招生日期');

            $table->unsignedSmallInteger('grades_count')->default(0)->comment('招几个班');
            $table->unsignedInteger('seats')->default(0)->comment('招生人数');
            $table->unsignedInteger('fee')->default(0)->comment('专业学费');
            $table->unsignedInteger('year')->comment('招生年度');
            $table->unsignedInteger('applied_count')->default(0)->comment('已报名人数');
            $table->unsignedInteger('enrolled_count')->default(0)->comment('已招生人数');

            $table->text('tags')->comment('标签: 使用逗号分隔');
            $table->text('tease')->comment('招生简章简介');
            $table->text('description')->comment('招生简章详情');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_plans');
    }
}
