<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationInformaticsMockDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            $data = [
                'nation' => '汉族',
                'political' => '党员',
                'source_place' => '北京',
                'native_place' => '北京朝阳',
                'mobile' => '18943210809',
                'qq' => '10010101',
                'wx' => '10010101',
                'email' => '10010101@qq.com',
                'parent_name' => '张大宝',
                'parent_mobile' => '18943210809',
            ];

            for ($i = 0; $i< 20; $i++) {
                $data['name']           = 'jack' . rand(1, 20);
                $data['major_id']       = rand(1, 10);
                $data['id_number']      = '0000000000000000' . rand(1, 20);
                $data['gender']         = rand(1, 2);
                $data['birth_time']     = date('Y-m-d');
                $data['whether_adjust'] = rand(1, 2);
                $data['status']         = rand(1, 3);
                DB::table('registration_informatics')->insert($data);
            }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registration_informatics_mock_data');
    }
}
