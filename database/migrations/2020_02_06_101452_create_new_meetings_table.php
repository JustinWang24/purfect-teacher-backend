<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('会议创建人');
            $table->integer('approve_userid')->comment('负责人');
            $table->integer('school_id')->comment('学校ID');
            $table->string('meet_title')->comment('标题');
            $table->text('meet_content')->comment('内容');
            $table->tinyInteger('signin_status')->default(0)->comment('签到状态 0:不需要 1:需要');
            $table->dateTimeTz('signin_start')->comment('签到开始时间')->nullable();
            $table->dateTimeTz('signin_end')->comment('签到结束时间')->nullable();
            $table->tinyInteger('signout_status')->default(0)->comment('签退状态 0:不需要 1:需要');
            $table->dateTimeTz('signout_start')->comment('签退开始时间')->nullable();
            $table->dateTimeTz('signout_end')->comment('签退结束时间')->nullable();
            $table->dateTimeTz('meet_start')->comment('会议开始时间');
            $table->dateTimeTz('meet_end')->comment('会议结束时间');
            $table->tinyInteger('type')->default(1)->comment('0:自定义地点 1:会议室');
            $table->tinyInteger('status')->default(0)->comment('状态 0:未审核 1:已通过 2:已拒绝');
            $table->integer('room_id')->comment('会议室')->nullable();
            $table->string('room_text')->comment('自定义地点')->nullable();
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE new_meetings comment '新会议' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_meetings');
    }
}
