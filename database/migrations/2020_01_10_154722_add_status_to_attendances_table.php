<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
            $table->tinyInteger('status')->default(0)->comment('评分状态 0:未评 1:已评');
        });
        DB::statement(" ALTER TABLE attendances comment '班级考勤评分表' ");

        Schema::table('attendances_details', function (Blueprint $table) {
            //
            $table->tinyInteger('score')->default(0)->comment('分数');
        });
        DB::statement(" ALTER TABLE attendances_details comment '班级考勤评分详情表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
            $table->dropColumn('status');
        });

        Schema::table('attendances_details', function (Blueprint $table) {
            //
            $table->dropColumn('score');
        });
    }
}
