<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('departments')){
            Schema::create('departments', function (Blueprint $table){
                $table->integerIncrements('id');
                // 该系关联的学校
                $table->unsignedInteger('school_id')->comment('学校ID');
                $table->unsignedInteger('campus_id')->default(0)->comment('校区ID');
                // 该系关联的学院, 如果为 0 无关联
                $table->unsignedInteger('institute_id')->default(0)->comment('学院ID');
                $table->string('name', 100)->comment('系名称');   // 学院(系)的名称
                $table->text('description')->comment('系描述');   // 学院(系)文字介绍
                // 最后更新该校区的信息的用户
                $table->unsignedBigInteger('last_updated_by')->default(0);
                $table->timestamps();
                $table->softDeletes();

            });
            DB::statement(" ALTER TABLE institutes comment '系表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
