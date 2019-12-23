<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTeachersMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_teachers_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('补卡申请人id');
            $table->unsignedInteger('manager_user_id')->nullable(false)->comment('补卡审批人id');
            $table->date('attendance_data')->nullable(false)->comment('忘打卡日期');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('1处理中2处理完成');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE attendance_teachers_messages comment '补卡申请记录' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_teachers_messages');
    }
}
