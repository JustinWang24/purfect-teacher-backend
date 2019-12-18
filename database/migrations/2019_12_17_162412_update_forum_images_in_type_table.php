<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForumImagesInTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_images', function (Blueprint $table) {
            $table->unsignedBigInteger('type')->comment('类型 1图片 2视频');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_images', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
