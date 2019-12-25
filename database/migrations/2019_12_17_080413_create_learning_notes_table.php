<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->comment('关联的学生');
            $table->unsignedInteger('year')->comment('学年');
            $table->unsignedSmallInteger('term')->comment('学期');
            $table->unsignedBigInteger('timetable_item_id')->comment('关联的课程表项');
            $table->text('note')->comment('文字内容');
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
        Schema::dropIfExists('learning_notes');
    }
}
