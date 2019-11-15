<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{

    /**
     * 文件目录表: categories
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('categories')) {

            Schema::create('categories', function (Blueprint $table) {

                $table->bigIncrements('id');
                $table->uuid('uuid')->index()->comment('目录的 uuid');
                $table->unsignedBigInteger('owner_id')->comment('目录所归属的的所有者的 user ID');
                $table->unsignedBigInteger('school_id')->comment('目录所归属的学校 ID');
                $table->unsignedSmallInteger('type')->default(0)->comment('目录的类别 0:根目录 1:子目录');
                $table->unsignedSmallInteger('asterisk')->default(0)->comment('是否有星标 0:无 1:是');
                $table->unsignedSmallInteger('public')->default(0)->comment('公开 0:不公开 1:公开');
                $table->unsignedBigInteger('parent_id')->default(0)->comment('目录的父级目录 id'); // 该目录的父级目录
                $table->timestamps();
                $table->string('name', 255)->comment('目录的名字');

            });

            DB::statement(" ALTER TABLE categories comment '文件目录表' ");
        }

        /**
         * 创建关联外键
         */
        if(Schema::hasTable('categories')){
		/**
		Schema::table('medias', function (Blueprint $table){
                // 级联删除文件
                $table->foreign('category_id')
                    ->references('id')->on('categories')
                    ->onDelete('cascade');
		});
		 */
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
