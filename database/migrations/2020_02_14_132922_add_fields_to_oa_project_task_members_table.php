<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOaProjectTaskMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_project_task_members', function (Blueprint $table) {
            //
            $table->tinyInteger('not_begin')->default(0)->comment('未开始状态 0:未读 1:已读');
            $table->tinyInteger('underway')->default(0)->comment('进行中状态 0:未读 1:已读');
            $table->tinyInteger('finish')->default(0)->comment('已完成状态 0:未读 1:已读');
        });
        Schema::table('oa_project_tasks', function (Blueprint $table) {
            $table->tinyInteger('read_status')->default(0)->comment('阅读状态 0:未读 1:已读');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oa_project_task_members', function (Blueprint $table) {
            //
            $table->dropColumn('not_begin');
            $table->dropColumn('underway');
            $table->dropColumn('finish');
        });

        Schema::table('oa_project_tasks', function (Blueprint $table) {
            $table->dropColumn('read_status');
        });
    }
}
