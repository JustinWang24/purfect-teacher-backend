<?php


namespace Tests\Feature\Account;

use Tests\Feature\BasicPageTestCase;

class AccountTest extends BasicPageTestCase
{
    /**
     * 测试正常获取充值中心
     */
    public function testItCanGetAccountCore()
    {
        $this->withoutExceptionHandling();
        $token = $this->getHeaderWithApiToken();
        $schoolUuId = $this->getHeaderWithUuidForSchool($this->getStudent());

        $header = array_merge($token, $schoolUuId);
        $data = [];

        $response = $this->post(route('api.account.core'), $data, $header);
        $result = json_decode($response->content(), true);

        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('data', $result,'返回数据中必须有 data 字段');
        $this->assertArrayHasKey('red_envelope', $result['data'],'返回数据中必须有 red_envelope 字段');
        $this->assertArrayHasKey('account_money', $result['data'],'返回数据中必须有 account_money 字段');
        $this->assertArrayHasKey('message', $result['data'],'返回数据中必须有 message 字段');
        $this->assertArrayHasKey('recharge', $result['data'],'返回数据中必须有 recharge 字段');

        foreach ($result['data']['recharge'] as $key => $val) {
            $this->assertArrayHasKey('id', $val,'返回数据中必须有 id 字段');
            $this->assertArrayHasKey('fictitious_money', $val,'返回数据中必须有 fictitious_money 字段');
            $this->assertArrayHasKey('actual_money', $val,'返回数据中必须有 actual_money 字段');
            $this->assertArrayHasKey('vip_money', $val,'返回数据中必须有 vip_money 字段');
        }

    }

}
