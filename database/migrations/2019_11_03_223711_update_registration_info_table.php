<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRegistrationInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 修复报名信息表的错误
        Schema::table('registration_informatics', function (Blueprint $table) {
            // 报名信息
            $table->unsignedInteger('recruitment_plan_id')->after('major_id')->comment('关联的招生计划 ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registration_informatics', function (Blueprint $table) {
            // 报名信息
            $table->dropColumn('recruitment_plan_id');
        });
    }
}
