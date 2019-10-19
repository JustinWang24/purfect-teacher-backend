<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('buildings')){

            Schema::create('buildings', function (Blueprint $table){
                $table->integerIncrements('id');
                // 关联的校区
                $table->unsignedBigInteger('school_id')->comment('校区id');
                $table->unsignedBigInteger('campus_id')->default(0); // 如果学校只有一个校区
                $table->string('name',40);
                $table->unsignedTinyInteger('type')->default(1)->comment('类型: 1:教学楼, 2:宿舍楼, 3:礼堂'); // 类型: 教学楼 1, 宿舍楼 2, 礼堂 3 ...
            });
        }
            DB::statement(" ALTER TABLE buildings comment '校区的建筑表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
