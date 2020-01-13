<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaProjectTaskMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_project_task_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('task_id')->comment('任务ID');
            $table->tinyInteger('status')->default(1)->comment('状态 1:未开始 2:正在进行 3:已结束');
            $table->string('remark')->comment('备注')->nullable();
            $table->dateTime('end_time')->comment('结束时间')->nullable();
            $table->timestamps();

        });
        DB::statement(" ALTER TABLE oa_project_task_members comment '项目成员表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_project_task_members');
    }
}
