<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyForumCommentReplys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_comment_replys', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->nullable(false)->default(0)->comment('评论的状态 0未审核，1审核通过，3删除');
            $table->unsignedInteger('school_id')->nullable(false)->default(0)->comment('学校id');
        });


        Schema::table('forum_comments', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->nullable(false)->default(0)->comment('评论的状态 0未审核，1审核通过，3删除');
            $table->unsignedInteger('school_id')->nullable(false)->default(0)->comment('学校id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_comment_replys', function (Blueprint $table) {
            if(Schema::hasColumn('forum_comment_replys','status')){
                $table->dropColumn('status');
            }
            if(Schema::hasColumn('forum_comment_replys','school_id')){
                $table->dropColumn('school_id');
            }
        });


        Schema::table('forum_comments', function (Blueprint $table) {
            if(Schema::hasColumn('forum_comments','status')){
                $table->dropColumn('status');
            }
            if(Schema::hasColumn('forum_comments','school_id')){
                $table->dropColumn('school_id');
            }
        });
    }
}
