<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTeachersGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_teachers_group_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('group_id')->nullable(false)->comment('考勤组表的关联id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('考勤组成员id');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('1成员2审批人');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE attendance_teachers_group_members comment '考勤组的成员用户表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_teachers_group_members');
    }
}
