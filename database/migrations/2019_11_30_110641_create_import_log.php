<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('type')->nullable(false)->comment('1:插入，2：修改，3：无操作标记');
            $table->string('source')->nullable(false)->comment('excel文件的原始记录json格式');
            $table->string('target')->nullable(true)->comment('数据库中已有的记录');
            $table->string('table_name')->nullable(false)->comment('需要写入的数据表名称');
            $table->string('result')->nullable(true)->comment('最终结果json格式');
            $table->unsignedInteger('task_id')->nullable(false)->comment('导入任务表的id');
            $table->unsignedInteger('task_status')->nullable(false)->comment('任务表本次操作的状态');
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
        Schema::dropIfExists('import_log');
    }
}
