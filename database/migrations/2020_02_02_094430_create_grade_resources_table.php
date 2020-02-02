<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('grade_id')->comment('班级ID');
            $table->string('name',100)->nullable()->comment('资源名称');
            $table->string('path')->comment('图片地址');
            $table->string('type',50)->default(1)->comment('文件的类型');
            $table->string('size', 100)->nullable()->comment('资源大小');
            $table->string('format',100)->nullable()->comment('资源格式');
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
        Schema::dropIfExists('grade_resources');
    }
}
