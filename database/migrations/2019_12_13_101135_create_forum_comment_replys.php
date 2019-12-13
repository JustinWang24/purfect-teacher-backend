<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumCommentReplys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_comment_replys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('comment_id')->nullable(false)->comment('评论id');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('to_user_id')->comment('目标用户ID');
            $table->unsignedBigInteger('forum_id')->comment('帖子ID');
            $table->text('reply')->comment('回复内容');
            $table->timestamps();
        });


        Schema::create('forum_like', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('forum_id')->comment('帖子ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->index('forum_id', 'idx_forum_id');
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
        Schema::dropIfExists('forum_comment_replys');
        Schema::dropIfExists('forum_like');
    }
}
