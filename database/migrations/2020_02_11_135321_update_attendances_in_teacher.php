<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttendancesInTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->tinyInteger('teacher_sign')->default(0)->comment('教师是否签到, 1签到 0未签到');
            $table->timestamp('teacher_sign_time')->nullable()->comment('教师签到时间');
        });

        Schema::dropIfExists('attendance_course_teachers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('teacher_sign');
            $table->dropColumn('teacher_sign_time');
        });
    }
}
