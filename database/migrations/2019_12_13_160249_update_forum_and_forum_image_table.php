<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForumAndForumImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 论坛主表
        Schema::table('forums', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->unsignedBigInteger('see_num')->default(0)->comment('看过数量')->change();
            $table->smallInteger('type_id')->nullable()->comment('类型')->change();
            $table->boolean('is_up')->nullable()->default(false)->comment('是否推荐')->change();
        });

        // 论坛图片表
        Schema::table('forum_images', function (Blueprint $table) {
            $table->dropColumn('media_id');
            $table->string('image')->nullable()->comment('图片url');
            $table->string('video')->nullable()->comment('视频url');
            $table->string('cover')->nullable()->comment('视频封面url');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forums', function (Blueprint $table) {
            $table->string('title');
            $table->unsignedBigInteger('see_num')->comment('看过数量')->change();
            $table->smallInteger('type_id')->comment('类型')->change();
            $table->boolean('is_up')->nullable()->comment('是否推荐')->change();
        });

        Schema::table('forum_images', function (Blueprint $table) {
            $table->dropColumn(['image', 'video', 'cover']);
        });
    }
}
