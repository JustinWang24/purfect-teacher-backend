<?php

namespace Tests\Feature\Textbook;

use App\Models\Schools\Textbook;
use Illuminate\Support\Str;
use Tests\Feature\BasicPageTestCase;

class TextbookTest extends BasicPageTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function _createDate() {
        $data['textbook'] = [
            'name'     => Str::random(),
            'press'    => '新华出版社',
            'author'   => '三毛',
            'edition'  => 1,      //版本
            'course_id'=> 1,
            'type'     => Textbook::TYPE_MAJOR,
            'purchase_price' => 80,
            'price'    => 100,
        ];
        return $data;
    }


    /**
     * 添加教材页面
     */
    public function testAddTextbookPage() {
        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.textbook.add'));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="textbook-name-input"');
        $response->assertSee('id="textbook-press-input"');
        $response->assertSee('id="textbook-author-input"');
        $response->assertSee('id="textbook-edition-input"');
        $response->assertSee('id="textbook-course_id-select"');
        $response->assertSee('id="textbook-type-input"');
        $response->assertSee('id="textbook-purchase_price"');
        $response->assertSee('id="textbook-price"');
        $response->assertSee('id="btn-edit-textbook"');
        $response->assertSee('link-return"');

    }


    /**
     * 添加教材接口
     */
    public function testAddTextbookApi() {

        $this->withoutExceptionHandling();
        $data = $this->_createDate();
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('school_manager.textbook.add',$data));
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
    }


    /**
     * 编辑页面
     */
    public function testEditTextbookPage() {
        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.textbook.edit'));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="textbook-id-input"');
        $response->assertSee('id="textbook-name-input"');
        $response->assertSee('id="textbook-press-input"');
        $response->assertSee('id="textbook-author-input"');
        $response->assertSee('id="textbook-edition-input"');
        $response->assertSee('id="textbook-course_id-select"');
        $response->assertSee('id="textbook-type-input"');
        $response->assertSee('id="textbook-purchase_price"');
        $response->assertSee('id="textbook-price"');
        $response->assertSee('id="btn-edit-textbook"');
        $response->assertSee('link-return"');
    }


    /**
     * 添加或编辑接口
     */
    public function testSaveTextbookApi() {
        $data = $this->_createDate();
//        $data['textbook']['id'] = 1;

        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('school_manager.textbook.save',$data));

        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

    }


    /**
     * 查看某专业教材的采购情况
     */
    public function testLoadMajorCourses() {
        $data = ['major_id'=>2971];
        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.textbook.loadMajorTextbook',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data']['major_textbook'] as $key => $val) {
            $this->assertArrayHasKey('code', $val);
            $this->assertArrayHasKey('name', $val);
            $this->assertArrayHasKey('year', $val);
            $this->assertArrayHasKey('type', $val);
            $this->assertArrayHasKey('textbook_num', $val);
            $this->assertArrayHasKey('textbooks', $val);
        }
    }



    /**
     * 教材列表
     */
    public function testTextbookListApi() {
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.textbook.list'));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        $this->assertArrayHasKey('textbook', $result['data']);

        foreach ($result['data']['textbook'] as $key => $val) {
            $this->assertArrayHasKey('name', $val);
            $this->assertArrayHasKey('press', $val);
            $this->assertArrayHasKey('author', $val);
            $this->assertArrayHasKey('course_id', $val);
            $this->assertArrayHasKey('type', $val);
            $this->assertArrayHasKey('purchase_price', $val);
            $this->assertArrayHasKey('price', $val);
            $this->assertArrayHasKey('course', $val);
            $this->assertArrayHasKey('name', $val['course']);
            $this->assertArrayHasKey('code', $val['course']);
            $this->assertArrayHasKey('year', $val['course']);

        }

    }



}
