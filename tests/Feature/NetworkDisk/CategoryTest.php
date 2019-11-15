<?php
namespace Tests\Feature\NetworkDisk;

use Carbon\Carbon;
use Tests\Feature\BasicPageTestCase;

class Category extends BasicPageTestCase
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

        $data = ['parent_id'=>2, 'name'=>Carbon::now()->year];
        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->post(route('api.categories.create'),$data,$header);

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
    }


    /**
     * 编辑目录
     */
    public function testEditCategories() {
        $data = [
            'parent_id'=>3,
            'name'=>Carbon::now()->year,
            'uuid'=>'caca77ce-7de7-44fd-a0c8-0e9479b60560',
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
     */
    public function testCateView() {

        $data = ['uuid'=>'623e728f-3701-4154-91a1-ddad1991d726'];
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


    public function testCateDelete() {

        $data = ['uuid'=>'caca77ce-7de7-44fd-a0c8-0e9479b60560'];
        $header = ['Authorization'=>"Bearer ".$this->token];
        $response = $this->get(route('api.categories.delete',$data),$header);
        dd($response);
    }


}
