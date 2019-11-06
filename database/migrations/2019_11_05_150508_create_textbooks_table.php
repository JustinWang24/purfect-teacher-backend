<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('textbooks')) {
            Schema::create('textbooks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->comment('教材名称');
                $table->string('press')->comment('出版社')->nullable();
                $table->string('author')->comment('作者')->nullable();
                $table->string('edition')->comment('版本')->nullable();
                $table->integer('course_id')->comment('课程ID')->nullable();
                $table->integer('school_id')->comment('学校ID');
                $table->tinyInteger('type')->comment('教材类型 1:专业教材 2:通用教材 3:选读教材');
                $table->float('purchase_price',8,2)->comment('采购价')->nullable();
                $table->float('price',8,2)->comment('学生购买价')->nullable();
                $table->text('introduce')->comment('教材介绍')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        DB::statement(" ALTER TABLE textbooks comment '教材表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('textbooks');
    }
}
