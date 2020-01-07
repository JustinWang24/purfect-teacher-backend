<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaAttendanceLeaveAndVisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_attendance_leave_and_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('申请人id');
            $table->unsignedInteger('manager_id')->nullable(true)->comment('审批人id');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->text('reason')->nullable(true)->comment('理由');
            $table->text('reply')->nullable(true)->comment('回复');
            $table->dateTime('start_time')->nullable(false)->comment('开始时间');
            $table->dateTime('end_time')->nullable(false)->comment('结束时间');
            $table->unsignedInteger('categoryid')->nullable(false)->comment('分类id');
            $table->unsignedInteger('type')->nullable(false)->comment('1请假,2外出');
            $table->unsignedInteger('status')->nullable(false)->default(0)->comment('0申请,1批准,2拒绝');
            $table->string('address')->nullable(true)->comment('外出地址');
            $table->string('des')->nullable(true)->comment('标题');
            $table->index('user_id');
            $table->index('school_id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_leave_and_visits comment '教师考勤请假和外出登记表' ");
        Schema::create('oa_attendance_leave_and_visit_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable(false)->comment('oa_attendance_leave_and_visit表的主键');
            $table->string('files')->nullable(false)->comment('图片地址');
            $table->index('parent_id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_attendance_leave_and_visit_files comment '教师考勤请假和外出登记表的附件表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_attendance_leave_and_visits');
        Schema::dropIfExists('oa_attendance_leave_and_visit_files');
    }
}
