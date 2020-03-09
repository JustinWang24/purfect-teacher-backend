<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAfternoonStartToTeacherAttendanceClocksetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_attendance_clocksets', function (Blueprint $table) {
            //
            $table->time('afternoon_start')->nullable(true)->comment('下午开始打卡');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_attendance_clocksets', function (Blueprint $table) {
            //
            if (Schema::hasColumn('teacher_attendance_clocksets', 'afternoon_start')) {
                $table->dropColumn('afternoon_start');
            }
        });
    }
}
