<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RefineStudentProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('student_profiles');

        // 依照 正方导出的模板作为参考
        Schema::create('student_profiles', function (Blueprint $table){
            $table->integerIncrements('id');
            $table->uuid('uuid')->index();
            // 关联的学生
            $table->unsignedBigInteger('user_id')->index();

            // 学生的登陆设备, 如果是手机, 则其串号保存在这里用来推送. 电脑的话为空
            $table->string('device')->default('n.a')->comment('学生的登陆设备');

            $table->unsignedMediumInteger('year')->comment('招生年度');
            $table->string('serial_number',20)->comment('录取编号');

            $table->unsignedTinyInteger('gender')->default(1)->comment('性别 1:男 2:女 ');

            // 学生的家乡信息
            $table->string('country',2)->default('cn')->comment('学生的家乡信息: 国家');
            $table->string('state',30)->nullable()->comment('省市名称');
            $table->string('city',30)->nullable()->comment('市名称');
            $table->string('postcode',6)->nullable()->comment('地区代码');
            $table->string('area',30)->nullable()->comment('地区名称');
            $table->text('address_line')->nullable()->comment('地址信息');

            // 考生信息
            $table->string('student_number',30)->nullable()->comment('考生号');
            $table->string('license_number',30)->nullable()->comment('准考证号');
            $table->string('id_number',30)->nullable()->comment('身份证号');

            // 学生在学校的住宿地址
            $table->text('address_in_school')->nullable()->comment('学生在学校的住宿地址');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('political_code',10)->nullable()->comment('政治面貌代码');
            $table->string('political_name',10)->nullable()->comment('政治面貌名称');
            $table->string('nation_code',10)->nullable()->comment('民族代码');
            $table->string('nation_name',20)->nullable()->comment('民族名称');

            // 学生的头像
            $table->string('avatar')->comment('头像');

            // ... 先写这么多 其他的可以再补充
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE student_profiles comment '学生用户详细资料表' ");

        // 根据考生的信息, 需要在学校表中加入以下的字段
        Schema::table('schools', function (Blueprint $table) {
            // 课程表需要一个 flag 来标识是否是草稿, 否则一旦添加, 就立即生效不符合流程
            // 比如更改了授课教师, 需要通知对方确认, 或者进入某个审核流程才行

            $table->string('state',30)->default('广东省')->comment('那个省');
            $table->string('level',30)->default('高职')->comment('学校批次名称');
            $table->string('category_code_state',30)
                ->default('B')->comment('省市科类代码');
            $table->string('category_code',30)
                ->default('01')->comment('学校科类代码');
            $table->string('category_name',30)
                ->default('理工')->comment('学校科类名称');
        });

        // 学院表也需要作出如下的调整
        Schema::table('institutes', function (Blueprint $table) {
            $table->string('category_code',30)
                ->default('01')->comment('学院代码');
        });

        // 专业表也需要作出如下的调整
        Schema::table('majors', function (Blueprint $table) {
            $table->string('category_code',30)
                ->default('01')->comment('专业代码');
            $table->unsignedMediumInteger('fee')
                ->default(0)->comment('学费');
            $table->unsignedSmallInteger('period')
                ->default(3)->comment('学制: 3年');
            $table->string('notes')
                ->nullable()->comment('本专业的备注');
        });
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
