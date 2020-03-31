<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateImportTaskTableAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_task', function (Blueprint $table) {
            $table->tinyInteger('type')->comment('类型: 0 无专业班级数据, 1有专业班级数据');
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
            $table->dropColumn('type');
        });
    }
}
