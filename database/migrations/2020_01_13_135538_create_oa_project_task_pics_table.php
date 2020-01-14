<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaProjectTaskPicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_project_task_pics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->comment('任务ID');
            $table->integer('task_member_id')->comment('任务会员ID');
            $table->string('url')->comment('地址');
            $table->dateTime('created_at');
        });
        DB::statement(" ALTER TABLE oa_project_task_pics comment '项目成员资源表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_project_task_pics');
    }
}
