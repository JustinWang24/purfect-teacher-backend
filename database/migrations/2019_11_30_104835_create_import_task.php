<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('状态 1：初始上传完成，2：导入中，3：导入完成，4回滚中，5回滚完成');
            $table->dateTime('upload_time')->nullable(false)->comment('上传时间');
            $table->unsignedInteger('manager_id')->nullable(false)->comment('操作员ID');
            $table->string('file_path')->nullable(false)->comment('文件存储路径');
            $table->string('config')->nullable(true)->comment('导入操作的配置文件json格式');
            $table->string('file_info')->nullable(true)->comment('文件基本信息，行数，列数，sheet数等json格式');
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
        Schema::dropIfExists('import_task');
    }
}
