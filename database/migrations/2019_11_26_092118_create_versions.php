<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique()->nullable(false)->comment('android App的最新版本号');
            $table->string('name')->comment('版本名称');
            $table->string('download_url')->nullable(true)->comment('apk文件的下载地址');
            $table->string('local_path')->nullable(true)->comment('apk文件的本地服务器存储位置');
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
        Schema::dropIfExists('versions');
    }
}
