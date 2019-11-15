<?php

namespace Tests\Feature\NetworkDisk;

use Illuminate\Http\UploadedFile;
use Tests\Feature\BasicPageTestCase;

class MediaTest extends BasicPageTestCase
{
    public $token;
    public function __construct()
    {
        parent::__construct();
        $this->token = '307e9df2-7434-4c45-bc1b-7241388e65e3';
    }


    /**
     * 测试文件上传
     */
    public function testUploadMedia() {

        $file = new UploadedFile(public_path().'/1.png','a.png');
//        $file = UploadedFile::fake()->image('random.jpg');
        $data = [
            'file' => $file,
            'uuid'=>'623e728f-3701-4154-91a1-ddad1991d726',
            'description'=>'测试文件上传',
            ];

        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->json('post',route('api.media.upload'),$data,$header);
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

        $this->assertArrayHasKey('file', $result['data']);
        $this->assertArrayHasKey('file_name', $result['data']['file']);
        $this->assertArrayHasKey('uuid', $result['data']['file']);
        $this->assertArrayHasKey('url', $result['data']['file']);
        $this->assertArrayHasKey('size', $result['data']['file']);
    }


    //
    public function testDeleteMedia() {

        $data = ['uuid'=>'c3c03c4e-cce6-4f67-99af-4fa1d972d837'];
        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->get(route('api.media.delete',$data),$header);
        dd($response);
    }
}
