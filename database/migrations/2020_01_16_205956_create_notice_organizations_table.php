<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('notice_id')->comment('消息ID');
            $table->integer('organization_id')->default(0)->comment('部门ID, 0为全部开放');
        });
        DB::statement(" ALTER TABLE notice_organizations comment '消息部门关联表' ");

        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notice_organizations');

        Schema::table('notices', function (Blueprint $table) {
            $table->integer('organization_id')->default(0)->comment('部门ID');
        });

    }
}
