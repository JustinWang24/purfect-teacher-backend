<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceTeacherMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_teachers_messages', function (Blueprint $table) {
            $table->time('attendance_time')->nullable(true)->comment('考勤时间');
            $table->string('content')->nullable(true)->comment('补卡理由');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_teachers_messages', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_teachers_messages','attendance_time')){
                $table->dropColumn('attendance_time');
            }
            if(Schema::hasColumn('attendance_teachers_messages','content')){
                $table->dropColumn('content');
            }
        });
    }
}
