<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 论坛主表
        Schema::create('forums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('学校ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->string('title', 100)->comment('标题');
            $table->string('content')->comment('内容');
            $table->unsignedBigInteger('see_num')->nullable(0)->comment('看过数量');
            $table->smallInteger('type_id')->comment('类型');
            $table->boolean('is_up')->nullable(false)->comment('是否推荐');
            $table->timestamps();
        });

        // 论坛图片表 和主表一多的关系
        Schema::create('forum_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('forum_id')->comment('论坛ID');
            $table->unsignedBigInteger('media_id');
            $table->timestamps();
        });

        // 论坛帖子类型表
        Schema::create('forum_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('学校ID');
            $table->string('title', 100)->comment('标题');
            $table->timestamps();
        });

        // 论坛帖子评论表
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('forum_id')->comment('帖子ID');
            $table->string('content', 100)->comment('评论内容');
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
        Schema::dropIfExists('forums');
        Schema::dropIfExists('forum_images');
        Schema::dropIfExists('forum_types');
        Schema::dropIfExists('forum_comments');
    }
}
