<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMajorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('majors')){
            Schema::create('majors', function (Blueprint $table){
                $table->integerIncrements('id');
                // 该专业关联的学校
                $table->unsignedInteger('school_id')->comment('学校ID');
                $table->unsignedInteger('campuses_id')->default(0)->comment('校区ID');
                $table->unsignedInteger('institutes_id')->default(0)->comment('学院ID');
                // 该专业关联的系, 如果为 0 表示无任何关联
                $table->unsignedInteger('department_id')->default(0)->comment('系ID');
                $table->string('name',100)->comment('专业名称');   // 学院(系)的名称
                $table->text('description')->comment('专业描述');   // 学院(系)文字介绍
                // 最后更新该校区的信息的用户
                $table->unsignedBigInteger('last_updated_by')->default(0);
                $table->timestamps();
                $table->softDeletes();

            });
            DB::statement(" ALTER TABLE majors comment '专业表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('majors');
    }
}
