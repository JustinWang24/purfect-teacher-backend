<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendElectiveCoursesAvailabilityPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 选修课的供学生可以选择的时间区间
         */
        Schema::table('school_configurations', function(Blueprint $table){
            $table->date('apply_elective_course_from_1')->default('1980-02-01')->comment('第一学期,选修课可以开始申请的起始日期');
            $table->date('apply_elective_course_to_1')->default('1980-02-07')->comment('第一学期,选修课可以开始申请的结束日期');
            $table->date('apply_elective_course_from_2')->default('1980-08-01')->comment('第2学期,选修课可以开始申请的起始日期');
            $table->date('apply_elective_course_to_2')->default('1980-08-07')->comment('第2学期,选修课可以开始申请的结束日期');
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
    }
}
