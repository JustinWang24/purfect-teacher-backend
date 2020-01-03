<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluateTeacherRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate_teacher_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evaluate_id')->comment('评价模板ID');
            $table->integer('evaluate_teacher_id')->comment('评教ID');
            $table->integer('user_id')->comment('学生ID');
            $table->integer('grade_id')->comment('班级ID');
            $table->tinyInteger('score')->default(10)->comment('评价得分');
            $table->string('desc')->comment('描述')->nullable();
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE evaluate_teacher_records comment '评教记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluate_teacher_records');
    }
}
