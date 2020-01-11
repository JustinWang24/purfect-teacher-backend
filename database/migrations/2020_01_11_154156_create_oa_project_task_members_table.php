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
