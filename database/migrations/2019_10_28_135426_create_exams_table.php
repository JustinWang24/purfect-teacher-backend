<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('exams')) {
            Schema::create('exams', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name','100')->comment('考试名称');
                $table->integer('school_id')->comment('学校ID');
                $table->integer('course_id')->comment('课程ID');
                $table->tinyInteger('semester')->comment('学期');
                $table->timestamps();
            });
        }

        DB::statement(" ALTER TABLE exams comment '考试表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
