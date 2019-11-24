<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTextbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_textbooks')) {
            Schema::create('course_textbooks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('course_id')->comment('课程ID');
                $table->integer('school_id')->comment('学校ID');
                $table->integer('textbook_id')->comment('教程ID');
                $table->timestamps();
                $table->softDeletes();

            });
        }
        DB::statement(" ALTER TABLE course_textbooks comment '课程教材关联表' ");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_textbooks');
    }
}
