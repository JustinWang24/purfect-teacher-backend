<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->integer('school_id')->comment('学校ID');
            $table->integer('media_id')->nullable()->comment('文件ID');
            $table->tinyInteger('status')->default(1)->comment('状态 1:开启 0:关闭');
        });
        DB::statement(" ALTER TABLE application_types comment '申请类型表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_types');
    }
}
