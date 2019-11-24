<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStudentEnrolledOptionalCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_enrolled_optional_courses', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable()->comment('学校的 ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_enrolled_optional_courses', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });
    }
}
