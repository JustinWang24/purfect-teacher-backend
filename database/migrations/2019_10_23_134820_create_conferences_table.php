<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('conferences')) {
            Schema::create('conferences', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title', 50)->comment('会议主题');
                $table->integer('school_id')->comment('学校ID');
                $table->integer('user_id')->comment('会议负责人');
                $table->integer('room_id')->comment('会议室ID');
                $table->tinyInteger('sign_out')->default(0)->comment('是否签退 0:不需要 1:需要');
                $table->timestamp('from')->comment('会议开始时间');
                $table->timestamp('to')->comment('会议结束时间')->nullable();
                $table->tinyInteger('video')->default(0)->comment('视频会议 0:不需要 1:需要');
                $table->text('remark')->comment('特殊说明')->nullable();
                $table->timestamps();
            });
        }
        DB::statement(" ALTER TABLE conferences comment '会议表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conferences');
    }
}
