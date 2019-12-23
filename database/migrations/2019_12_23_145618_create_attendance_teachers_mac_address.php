<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTeachersMacAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_teachers_mac_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('补卡申请人id');
            $table->unsignedInteger('manager_user_id')->nullable(true)->comment('补卡审批人id');
            $table->string('mac_address')->nullable(false)->comment('用户提交的macaddress');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('1处理中2处理完成');
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->string('content')->nullable(true)->comment('申请理由');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_teachers_mac_address');
    }
}
