<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMediasTable extends Migration
{

    /**
     * 媒体表
     */
    public function up()
    {

        if(!Schema::hasTable('medias')) {

            Schema::create('medias', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->uuid('uuid')->index()->comment('文件的 uuid');
                $table->unsignedBigInteger('user_id')->index()->comment('文件归属的用户的 ID');
                $table->unsignedSmallInteger('type')->default(1)->comment('所保存的文件的类型, 默认为图片类型 1');
                $table->unsignedBigInteger('category_id')->default(0)->comment('该文件所属的目录的 id');// 归类, 所属的目录
                $table->unsignedInteger('size')->default(0)->comment('文件的大小, 可以不填写, 但建议使用');    // 文件大小
                $table->unsignedInteger('period')->default(0)->comment('文件播放的时长, 仅对多媒体文件有效');  // 视频文件的的时长
                $table->unsignedTinyInteger('driver')->default(1)->comment('文件存放的服务器位置, 默认 1, 表示保存在本地');
                $table->dateTime('created_at');

                $table->string('file_name',255)->comment('文件原名');
                $table->string('keywords',100)->default('')->comment('描述该文件的关键字, 用于查找');
                $table->text('url')->comment('文件的真实 URI ');
                $table->text('description')->comment('文件的详细描述');

            });

            DB::statement("ALTER TABLE medias comment'媒体表'");
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
