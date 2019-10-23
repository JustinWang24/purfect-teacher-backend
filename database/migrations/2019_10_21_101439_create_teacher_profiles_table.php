<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('teacher_profiles')){
            Schema::create('teacher_profiles', function (Blueprint $table){
                $table->integerIncrements('id');
                $table->uuid('uuid')->index();
                // 关联的教师
                $table->unsignedBigInteger('teacher_id')->index()->comment('教师ID');
                //
                $table->unsignedBigInteger('school_id')->index()->comment('学校ID');
                $table->string('name',20);

                $table->unsignedTinyInteger('gender')->default(1)->comment('性别 0:女 1:男');

                // 教师的家乡信息
                $table->string('country',2)->default('cn')->comment('教师的家乡信息');
                $table->string('state',20)->nullable();
                $table->string('city',20)->nullable();
                $table->string('postcode',6)->nullable();
                $table->text('address_line')->nullable();

                // 教师在学校的住宿地址
                $table->text('address_in_school')->nullable()->comment('教师在学校的住宿地址');

                // 教师的登陆设备, 如果是手机, 则其串号保存在这里用来推送. 电脑的话为空
                $table->string('device')->nullable()->comment('教师的登陆设备');
                // 教师的生日
                $table->date('birthday')->nullable()->comment('生日');
                // 教师的头像
                $table->string('avatar')->comment('头像');

                // ... 先写这么多 其他的可以再补充
                $table->timestamps();
            });
            DB::statement(" ALTER TABLE teacher_profiles comment '教师用户详细资料表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_profiles');
    }
}
