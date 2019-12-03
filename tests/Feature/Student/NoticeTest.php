<?php
namespace Tests\Feature\Student;

use App\Models\Notices\Notice;
use App\Utils\JsonBuilder;
use Tests\Feature\BasicPageTestCase;

class NoticeTest extends BasicPageTestCase
{

    /**
     * 列表
     */
    public function testNoticeList() {
        $this->withoutExceptionHandling();
        $header = $this->getHeaderWithApiToken();
        $header['school_id'] = '1';
        $url = 'api.notice.list';
        $data = ['type'=>2];
        $response = $this->post(route($url),$data,$header);
//        dd($response->content());
        $result = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        $this->assertArrayHasKey('data', $result);
        if(!empty($result['data'])) {
            $this->assertArrayHasKey('currentPage', $result['data']);
            $this->assertArrayHasKey('lastPage', $result['data']);
            $this->assertArrayHasKey('total', $result['data']);
            $this->assertArrayHasKey('data', $result['data']);

            foreach ($result['data']['data'] as $key => $val) {
                $this->assertArrayHasKey('type', $val);
                $this->assertArrayHasKey('title', $val);
                $this->assertArrayHasKey('created_at', $val);
                if ($val['type'] == Notice::TYPE_NOTICE) {
                    $this->assertArrayHasKey('image_media', $val);
                    $this->assertArrayHasKey('url', $val['image_media']);
                }
                if ($val['type'] == Notice::TYPE_INSPECTION) {
                    $this->assertArrayHasKey('inspect', $val);
                    $this->assertArrayHasKey('name', $val['inspect']);
                }

            }
        }
    }


    /**
     * 详情
     */
    public function testNoticeInfo() {
        $this->withoutExceptionHandling();
        $header = $this->getHeaderWithApiToken();
        $url = 'api.notice.info';
        $data = ['notice_id'=>7];
        $response = $this->post(route($url),$data,$header);
        $result = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $result);
//        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
//            $result['code']);

        if($result['code'] == JsonBuilder::CODE_SUCCESS) {
            $this->assertArrayHasKey('notice', $result['data']);
            $this->assertArrayHasKey('id', $result['data']['notice']);
            $this->assertArrayHasKey('content', $result['data']['notice']);
            $this->assertArrayHasKey('title', $result['data']['notice']);
            $this->assertArrayHasKey('type', $result['data']['notice']);
            $this->assertArrayHasKey('notice_medias', $result['data']['notice']);
            foreach ($result['data']['notice']['notice_medias'] as $key => $val) {
                $this->assertArrayHasKey('url', $val);
            }

            if($result['data']['notice']['type'] == Notice::TYPE_NOTICE) {
                $this->assertArrayHasKey('image_media', $result['data']['notice']);

                foreach ($result['data']['notice']['image_media'] as $key => $val) {
                    $this->assertArrayHasKey('url', $val);
                }
            }

            if($result['data']['notice']['type'] == Notice::TYPE_INSPECTION) {
                $this->assertArrayHasKey('inspect', $result['data']['notice']);
                $this->assertArrayHasKey('name', $result['data']['notice']['inspect']);
            }



        }
    }
}
