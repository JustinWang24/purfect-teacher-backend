<?php
namespace Tests\Feature\NetworkDisk;

use Carbon\Carbon;
use Tests\Feature\BasicPageTestCase;

class CategoryTest extends BasicPageTestCase
{
    public $token;
    public function __construct()
    {
        parent::__construct();
        $this->token = '307e9df2-7434-4c45-bc1b-7241388e65e3';
    }

    /**
     * 创建目录
     */
    public function testCreateCategories() {

        $data = ['parent_id'=>2, 'name'=>Carbon::now()->year.rand(1,99)];
        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->post(route('api.categories.create'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        return $result;
    }


    /**
     * 编辑目录
     * @depends testCreateCategories
     */
    public function testEditCategories($return) {
        $data = [
            'parent_id'=>3,
            'name'=>Carbon::now()->year,
            'uuid'=>$return['data']['uuid'],
            'public'=>0,
            'asterisk'=>1,
        ];
        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->post(route('api.categories.edit'),$data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }


    /**
     * 获取当前目录下的内容
     * @depends testCreateCategories
     */
    public function testCateView($return) {

        $data = ['uuid'=>$return['data']['uuid']];
        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->get(route('api.categories.view',$data),$header);

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

        $this->assertArrayHasKey('category', $result['data']);
        $this->assertArrayHasKey('name', $result['data']['category']);
        $this->assertArrayHasKey('uuid', $result['data']['category']);
        $this->assertArrayHasKey('type', $result['data']['category']);
        $this->assertArrayHasKey('created_at', $result['data']['category']);
        $this->assertArrayHasKey('children', $result['data']['category']);
        $this->assertArrayHasKey('parent', $result['data']['category']);
        $this->assertArrayHasKey('files', $result['data']['category']);
        if(!empty($result['data']['category']['children'])) {
            foreach ($result['data']['category']['children'] as $key => $val) {
                $this->assertArrayHasKey('uuid', $val);
                $this->assertArrayHasKey('name', $val);
                $this->assertArrayHasKey('created_at', $val);
            }
        }

        if(!empty($result['data']['category']['files'])){
            foreach ($result['data']['category']['files'] as $key => $val) {
                $this->assertArrayHasKey('uuid', $val);
                $this->assertArrayHasKey('name', $val);
                $this->assertArrayHasKey('created_at', $val);
                $this->assertArrayHasKey('period', $val);
                $this->assertArrayHasKey('type', $val);
                $this->assertArrayHasKey('size', $val);
                $this->assertArrayHasKey('url', $val);
            }
        }

    }


    /**
     * 测试删除
     * @depends testCreateCategories
     */
    public function testCateDelete($return) {

        $data = ['uuid'=>$return['data']['uuid']];

        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->get(route('api.categories.delete',$data),$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }


}
