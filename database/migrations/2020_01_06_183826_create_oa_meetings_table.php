<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('会议创建人');
            $table->integer('school_id')->comment('学校ID');
            $table->string('meet_title',50)->comment('标题');
            $table->text('meet_content')->comment('内容');
            $table->string('meet_address',100)->comment('地址');
            $table->dateTime('signin_start')->comment('签到开始时间');
            $table->dateTime('signin_end')->comment('签到结束时间');
            $table->dateTime('meet_start')->comment('会议开始时间');
            $table->dateTime('meet_end')->comment('会议结束时间');
            $table->tinyInteger('signout_status')->default(0)->comment('是否签退 0否 1是');
            $table->dateTime('signout_start')->comment('最早签退时间')->nullable();
            $table->integer('approve_userid')->comment('负责人');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE oa_meetings comment '会议表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_meetings');
    }
}
