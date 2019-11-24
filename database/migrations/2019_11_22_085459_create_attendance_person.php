<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancePerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable(false)->index()->comment('用户id 学生老师都有可能');
            $table->unsignedTinyInteger('type')->nullable(false)->comment('人员类型，老师还是学生 1 老师 2 学生');
            $table->unsignedInteger('school_id')->comment('学校ID');
            $table->unsignedInteger('campus_id')->comment('校区ID');
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
        Schema::dropIfExists('attendance_persons');
    }
}
