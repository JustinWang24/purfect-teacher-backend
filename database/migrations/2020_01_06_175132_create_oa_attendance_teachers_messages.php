<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaAttendanceTeachersMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_attendance_teachers_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('补卡申请人id');
            $table->unsignedInteger('manager_user_id')->nullable(false)->comment('补卡审批人id');
            $table->unsignedInteger('time_slot')->nullable(false)->comment('时间段：1,上午 2,下午 3,晚上');
            $table->unsignedInteger('type')->nullable(false)->comment('操作类型：1,上班 2,下班 3,上下班');
            $table->date('attendance_date')->nullable(false)->comment('忘打卡日期');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('1处理中2处理完成');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->time('attendance_time')->nullable(true)->comment('考勤时间');
            $table->string('content')->nullable(true)->comment('补卡理由');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_teachers_messages comment '补卡申请记录' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_attendance_teachers_messages');
    }
}
