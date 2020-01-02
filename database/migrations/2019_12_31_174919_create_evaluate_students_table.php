<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evaluate_teacher_id')->comment('评教ID');
            $table->integer('user_id')->comment('学生ID');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE evaluate_students comment '评教被邀学生表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluate_students');
    }
}
