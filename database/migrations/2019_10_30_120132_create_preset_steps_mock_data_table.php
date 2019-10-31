<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresetStepsMockDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = ['拟稿' => '1', '核稿' => '2', '办公室主任核稿' => '3', '会签' => '4', '分管领导核稿' => '5',
                 '套红' => '6', '盖章' => '7', '发布' => '8', '归档' => '9', '送文单' => '10', '签收单' => '11',
                 '签收' => '12', '收文登记' => '13' , '拟办' => '14', '批办' => '15', '承办' => '16'
        ];

        foreach ($data as $key => $val) {
            $re['name']     = $key;
            $re['describe'] = $key.'---描述';
            $re['level']    = $val;
            $re['key']      = '';

            DB::table('preset_steps')->insert($re);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preset_steps_mock_data');
    }
}
