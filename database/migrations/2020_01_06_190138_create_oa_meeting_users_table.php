<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaMeetingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_meeting_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->comment('学校ID');
            $table->integer('meetid')->comment('会议ID');
            $table->integer('user_id')->comment('参会人员');
            $table->dateTime('start')->comment('签到时间')->nullable();
            $table->dateTime('end')->comment('签退时间')->nullable();
            $table->tinyInteger('my_signin_status')->default(0)->comment('签到状态 0:未签到 1:按时 2:迟到');
            $table->tinyInteger('my_signout_status')->default(0)->comment('签退状态 0:未签退 1:按时 2:早退');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_meeting_users comment '会议参会人员表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_meeting_users');
    }
}
