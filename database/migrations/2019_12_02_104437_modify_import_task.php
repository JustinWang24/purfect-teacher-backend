<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyImportTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_task', function (Blueprint $table) {
            $table->string('title')->comment('任务名称');
            $table->text('config')->change();
            if(Schema::hasColumn('import_task','upload_time')){
                $table->dropColumn('upload_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_task', function (Blueprint $table) {
            if(Schema::hasColumn('import_task','title')){
                $table->dropColumn('title');
            }
            $table->dateTime('upload_time')->nullable(false)->comment('上传时间');
        });
    }
}
