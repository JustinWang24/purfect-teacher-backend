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
            $table->dateTime('signin_start')->comment('签到时间')->nullable();
            $table->dateTime('signin_end')->comment('签退时间')->nullable();
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
