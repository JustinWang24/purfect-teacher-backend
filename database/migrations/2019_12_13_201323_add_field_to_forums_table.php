<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forums', function (Blueprint $table) {
            //
            $table->tinyInteger('status')->default(0)
                ->comment('状态  0:未审核 1:已审核');
        });

        DB::statement(" ALTER TABLE forums comment '论坛表' ");
        DB::statement(" ALTER TABLE forum_types comment '论坛类型表' ");
        DB::statement(" ALTER TABLE forum_like comment '论坛点赞表' ");
        DB::statement(" ALTER TABLE forum_images comment '论坛资源表' ");
        DB::statement(" ALTER TABLE forum_comments comment '论坛评论表' ");
        DB::statement(" ALTER TABLE forum_comment_replys comment '论坛评论回复表' ");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forums', function (Blueprint $table) {
            //
            $table->dropColumn('status');
        });
    }
}
