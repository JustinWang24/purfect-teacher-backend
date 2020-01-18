<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAttendanceUserMacsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_attendance_user_macs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_attendance_id')->comment('考勤配置 ID');
            $table->unsignedBigInteger('user_id')->comment('用户 ID');
            $table->string('mac_address')->comment('mac地址');
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
        Schema::dropIfExists('teacher_attendance_user_macs');
    }
}
