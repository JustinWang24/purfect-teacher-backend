<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('grade_id')->comment('班级ID');
            $table->integer('user_id')->comment('学生ID');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE student_leaves comment'学生请假表'");

        Schema::table('attendances_details', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('reason');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_leaves');

        Schema::table('attendances_details', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('请假状态 0:未审核 1:同意 2:拒绝');
            $table->string('reason')->nullable()->comment('请假原因');
        });
    }
}
