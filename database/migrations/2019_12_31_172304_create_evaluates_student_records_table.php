<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluatesStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluates_student_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('evaluate_id')->comment('评价模板ID');
            $table->integer('user_id')->comment('学生ID');
            $table->integer('teacher_id')->comment('教师ID');
            $table->tinyInteger('score')->default(10)->comment('评价得分');
            $table->string('desc')->comment('描述')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluates_student_records');
    }
}
