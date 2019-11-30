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
                $table->string('name',30)->comment('教材名称');
                $table->string('press',30)->comment('出版社');
                $table->string('author',20)->comment('作者');
                $table->string('edition',20)->comment('版本');
                $table->integer('school_id')->comment('学校ID');
                $table->unsignedTinyInteger('type')->comment('教材类型 1:专业教材 2:通用教材 3:选读教材')->default(\App\Models\Schools\Textbook::TYPE_MAJOR);
                $table->float('purchase_price',6,2)->comment('采购价')->default(0);
                $table->float('price',6,2)->comment('学生购买价')->default(0);
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
