<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStudentProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('student_profiles')){
            Schema::create('student_profiles', function (Blueprint $table){
                $table->integerIncrements('id');
                $table->uuid('uuid')->index();
                // 关联的学生
                $table->unsignedBigInteger('user_id')->index();
                $table->string('name',20);

                $table->unsignedTinyInteger('gender')->default(1)->comment('性别 0:女 1:男');

                // 学生的家乡信息
                $table->string('country',2)->default('cn')->comment('学生的家乡信息');
                $table->string('state',20)->nullable();
                $table->string('city',20)->nullable();
                $table->string('postcode',6)->nullable();
                $table->text('address_line')->nullable();

                // 学生在学校的住宿地址
                $table->text('address_in_school')->nullable()->comment('学生在学校的住宿地址');

                // 学生的登陆设备, 如果是手机, 则其串号保存在这里用来推送. 电脑的话为空

                // 学生的生日
                $table->date('birthday')->nullable()->comment('生日');
                // 学生的头像
                $table->string('avatar')->comment('头像');

                // ... 先写这么多 其他的可以再补充
                $table->timestamps();
            });
            DB::statement(" ALTER TABLE student_profiles comment '学生用户详细资料表' ");
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_profiles');
    }
}
