<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->comment('学校id');
            $table->string('title', 100)->comment('标题');
            $table->string('content')->comment('内容');
            $table->unsignedInteger('organization_id')->comment('可见部门');
            $table->unsignedInteger('media_id')->comment('附件ID');
            $table->unsignedBigInteger('image')->nullable()->comment('封面图ID');
            $table->timestamp('release_time')->nullable()->comment('发布时间');
            $table->string('note')->nullable()->comment('备注');
            $table->unsignedTinyInteger('inspect_id')->nullable()->comment('检查类型id');
            $table->unsignedTinyInteger('type')->comment('类型 1:通知, 2:公告, 3:检查');
            $table->unsignedBigInteger('user_id')->comment('操作人');
            $table->smallInteger('status')->comment('状态 0:待发布 1:已发布');
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
        Schema::dropIfExists('notices');
    }
}
