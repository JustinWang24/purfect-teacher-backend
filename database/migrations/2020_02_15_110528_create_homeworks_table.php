<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedMediumInteger('year')->comment('作业提交的学年');
            $table->unsignedBigInteger('student_id')->comment('学生');
            $table->text('content')->nullable()->comment('作业内容');
            $table->unsignedBigInteger('media_id')->nullable()->comment('作业内容');
            $table->text('comment')->nullable()->comment('教师或者学生的评语，备注');
            $table->unsignedMediumInteger('score')->comment('作业得分');
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
        Schema::dropIfExists('homeworks');
    }
}
