<?php

namespace Tests\Feature\Home;

use Tests\Feature\BasicPageTestCase;

class AppBannerTest extends BasicPageTestCase
{

    /**
     * 测试正常获取banner
     */
    public function testItCanGetBanner()
    {
        $this->withoutExceptionHandling();

        $header = $this->getHeaderWithApiToken();

        $header['school_uuid'] = '7a45642a-ef85-4f9b-96af-2ab546f2ffe5';

        $data = ['posit' => 1];
        $response = $this->post(route('api.banner.index'), $data, $header);
        $result = json_decode($response->content(), true);

        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('data', $result,'返回数据中必须有 data 字段');
        foreach ($result['data'] as $key => $val) {
            $this->assertArrayHasKey('id', $val,'返回数据中必须有 id 字段');
            $this->assertArrayHasKey('type', $val,'返回数据中必须有 type 字段');
            $this->assertArrayHasKey('title', $val,'返回数据中必须有 title 字段');
            $this->assertArrayHasKey('image_url', $val,'返回数据中必须有 image_url 字段');
        }
    }


}
