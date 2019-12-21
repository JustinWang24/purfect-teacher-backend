<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProjectAndTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_projects', function (Blueprint $table) {
            if(Schema::hasColumn('oa_projects','create_user')){
                $table->dropColumn('create_user');
            }
            if(Schema::hasColumn('oa_projects','is_open')){
                $table->dropColumn('is_open');
            }
        });
        Schema::table('oa_project_tasks', function (Blueprint $table) {
            if(Schema::hasColumn('oa_project_tasks','end_time')){
                $table->dropColumn('end_time');
            }
            if(Schema::hasColumn('oa_project_tasks','create_user')){
                $table->dropColumn('create_user');
            }
            if(Schema::hasColumn('oa_project_tasks','remark')){
                $table->dropColumn('remark');
            }
            if(Schema::hasColumn('oa_project_tasks','is_open')){
                $table->dropColumn('is_open');
            }
        });
        Schema::table('oa_projects', function (Blueprint $table) {
            $table->unsignedInteger('create_user')->comment('任务创建者id');
            $table->unsignedTinyInteger('is_open')->default(1)->comment('开放性 1-开放的 0-成员内的');
        });
        Schema::table('oa_project_tasks', function (Blueprint $table) {
            $table->dateTime('end_time')->nullable(true)->comment('任务结束时间');
            $table->unsignedInteger('create_user')->comment('任务创建者id');
            $table->string('remark')->comment('完成备注');
            $table->unsignedTinyInteger('is_open')->default(1)->comment('开放性 1-开放的 0-成员内的');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oa_projects', function (Blueprint $table) {
            if(Schema::hasColumn('oa_projects','create_user')){
                $table->dropColumn('create_user');
            }
            if(Schema::hasColumn('oa_projects','is_open')){
                $table->dropColumn('is_open');
            }
        });
        Schema::table('oa_project_tasks', function (Blueprint $table) {
            if(Schema::hasColumn('oa_project_tasks','end_time')){
                $table->dropColumn('end_time');
            }
            if(Schema::hasColumn('oa_project_tasks','create_user')){
                $table->dropColumn('create_user');
            }
            if(Schema::hasColumn('oa_project_tasks','remark')){
                $table->dropColumn('remark');
            }
            if(Schema::hasColumn('oa_project_tasks','is_open')){
                $table->dropColumn('is_open');
            }
        });
    }
}
