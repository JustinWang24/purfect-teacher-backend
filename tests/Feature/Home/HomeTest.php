<?php


namespace Tests\Feature\Home;

use Tests\Feature\BasicPageTestCase;

class HomeTest extends BasicPageTestCase
{

    /**
     * 测试正常获取 APP首页
     */
    public function testItCanGetHome()
    {
        $this->withoutExceptionHandling();
        $token = $this->getHeaderWithApiToken();
        $schoolUuId = $this->getHeaderWithUuidForSchool($this->getStudent());
        $header = array_merge($token, $schoolUuId);
        $data = [];

        $response = $this->post(route('api.home.index'), $data, $header);

    }

    /**
     * 测试正常生成二维码
     */
    public function testItCanGenerateQrCode()
    {
        $this->withoutExceptionHandling();
        $token = $this->getHeaderWithApiToken();
        $schoolUuId = $this->getHeaderWithUuidForSchool($this->getStudent());

        $header = array_merge($token, $schoolUuId);
        $data = [];

        $response = $this->post(route('api.generate.qr.code'), $data, $header);
        $result = json_decode($response->content(), true);

        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('data', $result,'返回数据中必须有 data 字段');
        $this->assertArrayHasKey('code', $result['data'],'返回数据中必须有 code 字段');
    }

}
