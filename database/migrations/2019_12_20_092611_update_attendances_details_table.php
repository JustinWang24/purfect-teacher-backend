<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttendancesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            //
            $table->tinyInteger('mold')->default('1')->comment('类型: 1:签到 2:请假 3:旷课');
            $table->tinyInteger('status')->default('0')->comment('请假状态 0:未审核 1:同意 2:拒绝');
            $table->string('reason')->comment('请假原因')->nullable();

        });
        Schema::dropIfExists('attendances_leaves');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            //
            $table->dropColumn('mold');
            $table->dropColumn('status');
            $table->dropColumn('reason');
        });

        Schema::create('attendances_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('timetable_id')->comment('课时ID');
            $table->mediumInteger('year')->comment('年份');
            $table->mediumInteger('term')->comment('学期');
            $table->string('reason')->comment('原因')->nullable();
            $table->date('date')->comment('课程的当前时间');
            $table->tinyInteger('status')->default(0)->comment('状态 0:未审核 1:同意 2:拒绝');
            $table->timestamps();
        });
    }
}
