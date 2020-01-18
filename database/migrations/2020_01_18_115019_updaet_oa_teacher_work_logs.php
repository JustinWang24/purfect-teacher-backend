<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdaetOaTeacherWorkLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oa_teacher_work_logs', function (Blueprint $table) {
            $table->integer('send_user_id')->nullable()->comment('发送人ID');
            $table->string('send_user_name', 100)->nullable()->comment('发送人姓名');
            $table->string('collect_user_id')->nullable()->comment('接收人ID 可能是多个 用, 拼接')->change();
            $table->string('collect_user_name')->nullable()->comment('接收人姓名');
            $table->integer('type')->comment('1 已接收 2已发送 3未发送')->change();
            $table->integer('status')->default(1)->comment('1 显示 0 不显示')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oa_teacher_work_logs', function (Blueprint $table) {
            $table->dropColumn('send_user_id');
            $table->dropColumn('send_user_name');
            $table->string('collect_user_id')->comment('接收人ID')->change();
            $table->integer('type')->nullable()->comment('x')->change();
            $table->integer('status')->nullable()->change();
        });
    }
}
