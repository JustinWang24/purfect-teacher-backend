<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDaynumberToTeacherAttendanceLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_attendance_leaves', function (Blueprint $table) {
            //
            $table->decimal('daynumber', 8, 2)->default(0)->comment('请假天数');
            $table->unsignedTinyInteger('teacher_attendance_id')->default(0)->comment('考勤组id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_attendance_leaves', function (Blueprint $table) {
            //
            if (Schema::hasColumn('teacher_attendance_leaves', 'daynumber')) {
                $table->dropColumn('daynumber');
            }
            if (Schema::hasColumn('teacher_attendance_leaves', 'teacher_attendance_id')) {
                $table->dropColumn('teacher_attendance_id');
            }
        });
    }
}
