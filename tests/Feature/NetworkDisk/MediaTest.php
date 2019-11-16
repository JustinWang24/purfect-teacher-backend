<?php

namespace Tests\Feature\NetworkDisk;

use App\User;
use Illuminate\Http\UploadedFile;
use Tests\Feature\BasicPageTestCase;

class MediaTest extends BasicPageTestCase
{

    /**
     * 测试文件上传
     */
    public function testUploadMedia() {

        $token = $this->getStudent()->user->api_token;
        $file = UploadedFile::fake()->image('random.jpg');

        /**
         * @var User $user
         */
        $user = $this->getStudent()->user;

        $data = [
            'file' => $file,
            'category'=>$user->networkDiskRoot->uuid,
            'description'=>'测试文件上传',
            ];

        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->json('post',route('api.media.upload'),$data,$header);

        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

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
        $token = $this->getStudent()['user']['api_token'];
        $data = ['uuid'=>$return['data']['file']['uuid']];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.getMediaInfo'),$data,$header);

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
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
        $token = $this->getStudent()['user']['api_token'];
        $data = ['keywords'=>'a'];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.search'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
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
        $token = $this->getStudent()['user']['api_token'];
        $data = ['uuid'=>$return['data']['file']['uuid']];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.click'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

    }



    /**
     * 测试删除
     * @depends testUploadMedia
     */
    public function testDeleteMedia($testUploadresult) {

        $token = $this->getStudent()['user']['api_token'];
        $data = ['uuid'=>$testUploadresult['data']['file']['uuid']];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.delete'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }


    /**
     * 最近浏览和上传
     */
    public function testLatelyCreateAndBrowse() {
        $this->withoutExceptionHandling();

        $token = $this->getStudent()['user']['api_token'];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.latelyUploadingAndBrowse'),[],$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
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
        $token = $this->getStudent()['user']['api_token'];
        $data = ['size'=>1000];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.judgeIsUpload'),$data,$header);
        $result = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        $this->assertArrayHasKey(  'total_size', $result['data']['size']);
        $this->assertArrayHasKey(  'use_size', $result['data']['size']);
        $this->assertArrayHasKey('is_upload', $result['data']['size']);
    }


    /**
     * 获取磁盘的大小
     */
    public function testGetNetWorkDiskSize() {
        $token = $this->getStudent()['user']['api_token'];
        $header = ['Authorization'=>"Bearer ".$token];
        $response = $this->post(route('api.media.getNetWorkDiskSize'),[],$header);

        $result = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        $this->assertArrayHasKey(  'total_size', $result['data']['size']);
        $this->assertArrayHasKey(  'use_size', $result['data']['size']);
    }



}
