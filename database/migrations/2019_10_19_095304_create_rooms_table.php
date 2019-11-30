<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('rooms')){
            Schema::create('rooms', function (Blueprint $table){
            $table->integerIncrements('id');
            // 关联的校区
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('campus_id')->default(0); // 如果学校只有一个校区
            $table->unsignedBigInteger('building_id'); // 如果学校只有一个校区
            $table->string('name',40);
            $table->smallInteger('exam_seats')->comment('考试可容纳的人数')->nullable();
            $table->unsignedTinyInteger('type')->default(1)->comment('类型: 1教室, 2会议室, 3学生宿舍'); //
            });
        }
        DB::statement(" ALTER TABLE rooms comment '校区的建筑的房间表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }

}
