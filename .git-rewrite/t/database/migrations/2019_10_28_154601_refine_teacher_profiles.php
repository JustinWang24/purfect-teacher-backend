<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefineTeacherProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('teacher_profiles');

        // 依照 正方导出的模板作为参考
        Schema::create('teacher_profiles', function (Blueprint $table){
            $table->integerIncrements('id');
            $table->uuid('uuid')->index();
            // 关联的 user id
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('school_id')->comment('教师任职的学校');
            $table->string('serial_number',20)->comment('教师编号');
            $table->string('group_name',20)->comment('所在部门: 基础教学部');
            $table->string('title',20)->comment('教师职称: 教授, 讲师');
            $table->unsignedTinyInteger('gender')->default(1)->comment('性别 1:男 2:女 ');
            $table->string('id_number',30)->nullable()->comment('身份证号');
            $table->date('birthday')->nullable()->comment('生日');
            $table->date('joined_at')->nullable()->comment('入职日期');
            $table->string('political_code',10)->nullable()->comment('政治面貌代码');
            $table->string('political_name',10)->nullable()->comment('政治面貌名称');
            $table->string('nation_code',10)->nullable()->comment('民族代码');
            $table->string('nation_name',20)->nullable()->comment('民族名称');
            $table->string('education',20)->nullable()->comment('学历');
            $table->string('degree',20)->nullable()->comment('学位');
            $table->string('avatar')->comment('头像');
            // ... 先写这么多 其他的可以再补充
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
        //
    }
}
