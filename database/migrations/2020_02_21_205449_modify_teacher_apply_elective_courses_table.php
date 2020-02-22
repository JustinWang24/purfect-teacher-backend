<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTeacherApplyElectiveCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('campus_id')->default(0)->comment('申请的学院');
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
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            //
            if (Schema::hasColumn('teacher_apply_elective_courses', 'campus_id')) {
                $table->dropColumn('campus_id');
            }
        });
    }
}
