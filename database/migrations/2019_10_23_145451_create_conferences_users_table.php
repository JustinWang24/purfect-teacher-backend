<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferencesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('conferences_users')) {
            Schema::create('conferences_users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('conference_id')->comment('会议ID');
                $table->integer('user_id')->comment('参会人ID');
                $table->integer('status')->default(0)->comment('状态 0:未签到 1:签到');
                $table->timestamp('from')->comment('开始签到时间')->nullable();
                $table->timestamp('to')->comment('结束签到时间')->nullable();
                $table->timestamps();
            });
        }
        DB::statement(" ALTER TABLE conferences_users comment '会议参会人表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conferences_users');
    }
}
