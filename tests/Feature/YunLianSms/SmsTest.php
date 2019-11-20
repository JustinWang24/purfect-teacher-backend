<?php


namespace Tests\Feature\YunLianSms;

use Tests\Feature\BasicPageTestCase;
use App\Utils\Misc\SmsFactory;

class SmsTest extends BasicPageTestCase
{

    /**
     * 测试正确云联接口
     */
    public function testYunLianSms()
    {
        $sms =  SmsFactory::GetInstance();
        $res = $sms->send('15235665252', '483489', ['小明', '北京大学', '计算机']);
        $this->assertTrue($res->getCode() == 1000);
    }


}
