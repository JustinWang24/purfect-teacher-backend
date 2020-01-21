<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOaTeacherHelperPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_teacher_helper_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('school_id')->comment('学校id');
            $table->string('name', 100)->comment('名称');
            $table->tinyInteger('type')->comment('属于谁 0都可以看到 9教师 10教工');
            $table->tinyInteger('status')->default(1)->comment('状态 0不显示 1显示');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE oa_teacher_helper_type comment '教师端助手页类型表' ");

        Schema::create('oa_teacher_helper_page', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type_id')->comment('类型id');
            $table->string('name', 100)->comment('名称');
            $table->string('icon')->comment('icon图标');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE oa_teacher_helper_page comment '教师端助手页详情表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oa_teacher_helper_type');
        Schema::dropIfExists('oa_teacher_helper_page');
    }
}
