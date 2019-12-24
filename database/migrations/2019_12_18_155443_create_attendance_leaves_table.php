<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('timetable_id')->comment('课时ID');
            $table->mediumInteger('year')->comment('年份');
            $table->mediumInteger('term')->comment('学期');
            $table->string('reason')->comment('原因')->nullable();
            $table->date('date')->comment('课程的当前时间');
            $table->tinyInteger('status')->default(0)->comment('状态 0:未审核 1:同意 2:拒绝');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE attendances_leaves comment '请假表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances_leaves');
    }
}
