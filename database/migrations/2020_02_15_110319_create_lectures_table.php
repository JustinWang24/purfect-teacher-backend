<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id')->comment('对应的课程');
            $table->unsignedBigInteger('teacher_id')->comment('对应的授课教师');
            $table->unsignedSmallInteger('idx')->comment('对应的课程的第几节课');
            $table->string('title')->nullable()->comment('课节的标题: 朱自清');
            $table->text('summary')->nullable()->comment('课节的简介');
            $table->text('tags')->nullable()->comment('标签');
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
        Schema::dropIfExists('lectures');
    }
}
