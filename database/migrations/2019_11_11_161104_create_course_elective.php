<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseElective extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_electives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('course_id')->nullable()->comment('课程的 ID');
            $table->unsignedSmallInteger('open_num')->default(1)->nullable()->comment('开班人数设置');
            $table->unsignedInteger('room_id')->comment('教室ID');
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
        Schema::dropIfExists('course_electives');
    }
}
