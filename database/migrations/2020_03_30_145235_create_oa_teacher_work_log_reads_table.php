<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaTeacherWorkLogReadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_teacher_work_log_reads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('work_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_teacher_work_log_reads comment '工作日志已读表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_teacher_work_log_reads');
    }
}
