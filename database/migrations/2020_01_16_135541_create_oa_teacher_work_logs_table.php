<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaTeacherWorkLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_teacher_work_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('发送人ID');
            $table->string('collect_user_id')->comment('接收人ID');
            $table->string('title', 50)->comment('标题');
            $table->string('content')->comment('内容');
            $table->tinyInteger('type')->comment('1未读, 2已读, 3已发送, 4草稿箱');
            $table->tinyInteger('status')->comment('状态 0不显示 1显示');
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
        Schema::dropIfExists('oa_teacher_work_logs');
    }
}
