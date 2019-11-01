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

//        $data = [
//            'nation' => '汉族',
//            'political' => '党员',
//            'source_place' => '北京',
//            'native_place' => '北京朝阳',
//            'mobile' => '18943210809',
//            'qq' => '10010101',
//            'wx' => '10010101',
//            'email' => '10010101@qq.com',
//            'parent_name' => '张大宝',
//            'parent_mobile' => '18943210809',
//        ];
//
//        for ($i = 0; $i< 20; $i++) {
//            $data['name']           = 'jack' . rand(1, 20);
//            $data['school_id']      = 1;
//            $data['major_id']       = rand(1, 10);
//            $data['id_number']      = '0000000000000000' . rand(1, 20);
//            $data['gender']         = rand(1, 2);
//            $data['birth_time']     = date('Y-m-d');
//            $data['whether_adjust'] = rand(1, 2);
//            $data['status']         = rand(1, 3);
//            DB::table('registration_informatics')->insert($data);
//        }
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
