<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCourseElectives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_electives', function (Blueprint $table) {
            $table->unsignedInteger('status')->nullable(false)->default(1)->comment('选修课状态:1: 等待, 2: 成功开课, 3: 人数报满，停止报名, 4: 被管理员取消')->change();
            $table->unsignedInteger('start_year')->nullable(false)->comment('课程开始年度');
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
