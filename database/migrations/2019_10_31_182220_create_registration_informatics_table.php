<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationInformaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_informatics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('major_id')->comment('报名的专业ID');
            $table->string('name', '50')->comment('姓名');
            $table->char('id_number', '18')->comment('身份证号');
            $table->unsignedTinyInteger('gender')->comment('性别 0:女 1:男');
            $table->timestamp('birth_time')->comment('出生年月日');
            $table->string('nation', '20')->comment('民族');
            $table->string('political', '10')->comment('政治面貌');
            $table->string('source_place', '50')->comment('生源地');
            $table->string('native_place', '50')->comment('籍贯');
            $table->string('mobile', '11')->comment('手机号');
            $table->string('qq', '20')->comment('QQ号');
            $table->string('wx', '30')->comment('微信号');
            $table->string('email', '50')->comment('邮箱');
            $table->string('parent_name', '50')->comment('家长姓名');
            $table->string('parent_mobile', '11')->comment('家长手机号');
            $table->string('mid_exam', '11')->nullable()->comment('中考分数');
            $table->string('high_exam', '11')->nullable()->comment('高考分数');
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
        Schema::dropIfExists('registration_information');
    }
}
