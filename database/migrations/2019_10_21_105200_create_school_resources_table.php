<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_resources', function (Blueprint $table) {
            $table->integerIncrements('id');
            // 关联的学校
            $table->unsignedBigInteger('school_id')->index()->comment('学校ID');;
            $table->string('name',100)->comment('资源名称');
            $table->string('path',200)->comment('资源路径');
            $table->unsignedSmallInteger('type')->default(1)->comment('文件的类型, 默认为图片类型 1 图片 2视频');
            $table->string('size',20)->comment('资源大小');
            $table->string('format',20)->comment('资源格式');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE school_resources comment '学校资源表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_resources');
    }
}
