<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RebuildBasicEnrolmentStepsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 第一个都是欢迎语, 从第个记录开始检查, 更新为产品要求的新流程名称
        $dao = new \App\Dao\EnrolmentStep\EnrolmentStepDao();
        $second = $dao->getById(2);
        if($second->name !== '交学费'){
            // 开始更新操作
            $newData = [
                '确认新生报到',
                '核实新生信息',
                '更改报到状态',
                '领取管理',
                '学费',
                '书费',
                '住宿费',
                '其他费用',
            ];
        }
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
