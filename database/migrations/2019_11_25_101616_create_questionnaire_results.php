<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->index()->comment('学校ID');
            $table->unsignedInteger('questionnaire_id')->nullable(false)->comment('问卷ID');
            $table->unsignedBigInteger('user_id')->nullable(false)->index()->comment('用户id 学生老师都有可能');
            $table->unsignedInteger('result')->nullable(false)->index()->comment('答题结果');
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
        Schema::dropIfExists('questionnaire_results');
    }
}
