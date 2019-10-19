<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('grades')){
            Schema::create('grades', function (Blueprint $table){
                $table->integerIncrements('id');
                // 该专业关联的学校
                $table->unsignedInteger('school_id');
                // 该班级关联的专业, 如果为 0 表示无任何关联
                $table->unsignedInteger('major_id')->default(0);
                // 最后更新该班级的信息的用户
                $table->unsignedMediumInteger('year');
                $table->unsignedBigInteger('last_updated_by')->default(0);
                $table->timestamps();
                $table->softDeletes();
                $table->string('name',100);   // 学院(系)的名称
                $table->text('description');   // 学院(系)文字介绍
            });
            DB::statement(" ALTER TABLE grades comment '班级表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
