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

        $header = $this->getHeaderWithApiToken();
        $header['school_uuid'] = '7a45642a-ef85-4f9b-96af-2ab546f2ffe5';

        $data = [];

        $response = $this->post(route('api.home.index'), $data, $header);


    }

}
