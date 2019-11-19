<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyCourseMajors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 课程所归属的专业, 比如 数学课 可能有多个专业都要学
        Schema::create('apply_course_majors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('apply_id');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('major_id');
            $table->string('major_name')->comment('专业名');
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
        Schema::dropIfExists('apply_course_majors');
    }
}
