<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsRoomsStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('exams_rooms_students')) {
            Schema::create('exams_rooms_students', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('exam_room_id')->comment('考点ID');
                $table->integer('user_id')->comment('用户ID');
                $table->integer('grade_id')->comment('班级ID');
                $table->integer('seat_num')->comment('座位号');
                $table->tinyInteger('status')->comment('状态')->nullable();
                $table->timestamps();
            });
        }
        DB::statement(" ALTER TABLE exams_rooms_students comment '考点学生信息' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams_rooms_students');
    }
}
