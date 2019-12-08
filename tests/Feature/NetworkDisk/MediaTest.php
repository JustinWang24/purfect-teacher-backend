<?php

namespace Tests\Feature\NetworkDisk;

use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\UploadedFile;
use Tests\Feature\BasicPageTestCase;

class MediaTest extends BasicPageTestCase
{

    /**
     * 测试文件上传
     */
    public function testUploadMedia() {

        $file = UploadedFile::fake()->image('random.jpg');

        /**
         * @var User $user
         */
        $user = $this->getStudent()->user;

        $data = [
            'file' => $file,
//            'category'=>$user->networkDiskRoot->uuid,
            'description'=>'测试文件上传',
            ];

        $header = $this->getHeaderWithApiToken();
        $response = $this->json('post',route('api.media.upload'),$data,$header);
        dd($response->content());
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);

        $this->assertArrayHasKey('file', $result['data']);
        $this->assertArrayHasKey('file_name', $result['data']['file']);
        $this->assertArrayHasKey('uuid', $result['data']['file']);
        $this->uuid = $result['data']['file']['uuid'];
        $this->assertArrayHasKey('url', $result['data']['file']);
        $this->assertArrayHasKey('size', $result['data']['file']);


        return $result;
    }


    /**
     * 测试文件详情
     * @depends testUploadMedia
     */
    public function testGetMediaInfo($return) {

        $header = $this->getHeaderWithApiToken();
        $data = ['uuid'=>$return['data']['file']['uuid']];
        $response = $this->post(route('api.media.getMediaInfo'),$data,$header);

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        $this->assertArrayHasKey('media', $result['data']);
        $this->assertArrayHasKey('file_name', $result['data']['media']);
        $this->assertArrayHasKey('description', $result['data']['media']);
        $this->assertArrayHasKey('size', $result['data']['media']);
        $this->assertArrayHasKey('created_at', $result['data']['media']);
    }

    /**
     * 测试搜索
     */
    public function testSearchMedia() {
        $header = $this->getHeaderWithApiToken();
        $data = ['keywords'=>'a'];
        $response = $this->post(route('api.media.search'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        $this->assertArrayHasKey('files', $result['data']);
        if(!empty($result['data']['files'])) {
            foreach ($result['data']['files'] as $key => $val) {
                $this->assertArrayHasKey('file_name', $val);
                $this->assertArrayHasKey('uuid', $val);
                $this->assertArrayHasKey('url', $val);
                $this->assertArrayHasKey('size', $val);
                $this->assertArrayHasKey('period', $val);
            }
        }

    }


    /**
     * 测试点击次数
     * @depends testUploadMedia
     */
    public function testMediaClick($return) {
        $header = $this->getHeaderWithApiToken();
        $data = ['uuid'=>$return['data']['file']['uuid']];
        $response = $this->post(route('api.media.click'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);

    }



    /**
     * 测试删除
     * @depends testUploadMedia
     */
    public function testDeleteMedia($testUploadresult) {

        $header = $this->getHeaderWithApiToken();
        $data = ['uuid'=>$testUploadresult['data']['file']['uuid']];
        $response = $this->post(route('api.media.delete'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
    }


    /**
     * 最近浏览和上传
     */
    public function testLatelyCreateAndBrowse() {
        $this->withoutExceptionHandling();

        $header = $this->getHeaderWithApiToken();
        $response = $this->post(route('api.media.latelyUploadingAndBrowse'),[],$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        $this->assertArrayHasKey('uploading', $result['data']);
        $this->assertArrayHasKey('browse', $result['data']);
        if(!empty($result['data']['browse'])) {
            foreach ($result['data']['browse'] as $key => $val) {
                $this->assertArrayHasKey('url', $val);
                $this->assertArrayHasKey('created_at', $val);
                $this->assertArrayHasKey('keywords', $val);
                $this->assertArrayHasKey('file_name', $val);
                $this->assertArrayHasKey('asterisk', $val);
            }


        }
        if(!empty($result['data']['uploading'])) {
            foreach ($result['data']['uploading'] as $key => $val){
                $this->assertArrayHasKey('url', $val);
                $this->assertArrayHasKey('created_at', $val);
                $this->assertArrayHasKey('keywords', $val);
                $this->assertArrayHasKey('file_name', $val);
                $this->assertArrayHasKey('asterisk', $val);
            }
        }

    }


    /**
     * 判断是否可以上传
     */
    public function testJudgeIsUpload() {
        $header = $this->getHeaderWithApiToken();
        $data = ['size'=>1000];
        $response = $this->post(route('api.media.judgeIsUpload'),$data,$header);
        $result = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        $this->assertArrayHasKey(  'total_size', $result['data']['size']);
        $this->assertArrayHasKey(  'use_size', $result['data']['size']);
        $this->assertArrayHasKey('is_upload', $result['data']['size']);
    }


    /**
     * 获取磁盘的大小
     */
    public function testGetNetWorkDiskSize() {
        $header = $this->getHeaderWithApiToken();
        $response = $this->post(route('api.media.getNetWorkDiskSize'),[],$header);

        $result = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        $this->assertArrayHasKey(  'total_size', $result['data']['size']);
        $this->assertArrayHasKey(  'use_size', $result['data']['size']);
    }



}
