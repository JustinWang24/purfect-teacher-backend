<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectureMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedBigInteger('teacher_id'); // 冗余字段，为了查询方便
            $table->unsignedBigInteger('course_id'); // 冗余字段，为了查询方便
            $table->unsignedBigInteger('media_id')->default(0)->comment('对应的教师上传的文件id');
            $table->unsignedSmallInteger('type')->comment('材料类型: 预习, 复习, 课外阅读, 课堂讲义');
            $table->text('description')->nullable()->comment('材料的描述');
            $table->text('url')->nullable()->comment('文件或者链接的地址');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecture_materials');
    }
}
