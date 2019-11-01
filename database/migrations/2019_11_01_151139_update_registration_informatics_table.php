<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRegistrationInformaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::dropIfExists('registration_informatics');

         Schema::create('registration_informatics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('学生ID');
            $table->unsignedInteger('school_id')->comment('报名的学校ID');
            $table->unsignedInteger('major_id')->comment('报名的专业ID');
            $table->string('name', '20')->comment('姓名');
            $table->unsignedTinyInteger('whether_adjust')->comment('是否接受调剂 1接受 0不接受');
            $table->unsignedTinyInteger('status')->comment('状态 1待审核 2已通过 3未通过');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE registration_informatics comment '报名信息表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
