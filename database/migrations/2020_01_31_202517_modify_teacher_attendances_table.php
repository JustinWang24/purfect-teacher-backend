<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTeacherAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('teacher_attendance_clocksets', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_weekday')->comment('是否工作日 1是 0否');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('teacher_attendance_clocksets', function (Blueprint $table) {
            //
            if (Schema::hasColumn('teacher_attendance_clocksets', 'is_weekday')) {
                $table->dropColumn('is_weekday');
            }
        });

    }
}
