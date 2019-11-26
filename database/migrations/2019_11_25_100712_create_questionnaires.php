<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->index()->comment('学校ID');
            $table->string('title')->nullable(false)->comment('问卷的标题');
            $table->text('detail')->nullable(true)->comment('问卷的具体介绍');
            $table->string('first_question_info')->nullable(false)->comment('第一个问题,对应选项1');
            $table->string('second_question_info')->nullable(false)->comment('第二个问题,对应选项2');
            $table->string('third_question_info')->nullable(false)->comment('第三个问题,对应选项3');
            $table->unsignedTinyInteger('status')->nullable(false)->default(1)->comment('1 锁定状态， 2 激活状态');
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
        Schema::dropIfExists('questionnaires');
    }
}
