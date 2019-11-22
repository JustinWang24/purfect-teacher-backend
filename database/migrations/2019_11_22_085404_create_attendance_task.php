<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校ID');
            $table->unsignedInteger('campus_id')->comment('校区ID');
            $table->unsignedTinyInteger('type')->nullable(false)->default(1)->comment('任务类型 1 教师任务， 2 学生任务, 3 其它任务(学生老师都可见)');
            $table->string('title', 255)->nullable(true)->comment('任务名称');
            $table->text('content')->nullable(true)->comment('值周内容');
            $table->date('start_time')->nullable(true)->comment('任务开始时间');
            $table->date('end_time')->nullable(true)->comment('任务截止时间');
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
        Schema::dropIfExists('attendance_tasks');
    }
}
