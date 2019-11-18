<?php

namespace Tests\Feature\Textbook;

use App\Models\Schools\Textbook;
use App\Utils\JsonBuilder;
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
            'author'   => '鲁迅',
            'edition'  => 1,      //版本
            'type'     => Textbook::TYPE_MAJOR,
            'purchase_price' => 80,
            'price'    => 100,
        ];
        return $data;
    }


    /**
     * 添加教材页面
     */
//    public function testAddTextbookPage() {
//        $this->withoutExceptionHandling();
//        $user = $this->getSuperAdmin();
//
//        $response = $this->setSchoolAsUser($user, 50)
//            ->actingAs($user)
//            ->withSession($this->schoolSessionData)
//            ->get(route('teacher.textbook.add'));
//
//        $response->assertSee('input type="hidden" name="_token"');
//        $response->assertSee('id="textbook-name-input"');
//        $response->assertSee('id="textbook-press-input"');
//        $response->assertSee('id="textbook-author-input"');
//        $response->assertSee('id="textbook-edition-input"');
//        $response->assertSee('id="textbook-type-input"');
//        $response->assertSee('id="textbook-purchase_price"');
//        $response->assertSee('id="textbook-price"');
//        $response->assertSee('id="btn-edit-textbook"');
//        $response->assertSee('link-return"');
//
//    }


    /**
     * 编辑页面
     */
//    public function testEditTextbookPage() {
//        $this->withoutExceptionHandling();
//        $user = $this->getSuperAdmin();
//
//        $response = $this->setSchoolAsUser($user, 50)
//            ->actingAs($user)
//            ->withSession($this->schoolSessionData)
//            ->get(route('teacher.textbook.edit'));
//
//        $response->assertSee('input type="hidden" name="_token"');
//        $response->assertSee('id="textbook-id-input"');
//        $response->assertSee('id="textbook-name-input"');
//        $response->assertSee('id="textbook-press-input"');
//        $response->assertSee('id="textbook-author-input"');
//        $response->assertSee('id="textbook-edition-input"');
//        $response->assertSee('id="textbook-type-input"');
//        $response->assertSee('id="textbook-purchase_price"');
//        $response->assertSee('id="textbook-price"');
//        $response->assertSee('id="btn-edit-textbook"');
//        $response->assertSee('link-return"');
//    }


    /**
     * 添加或编辑接口
     */
    public function testSaveTextbookApi() {
        $data = $this->_createDate();
//        $data['textbook']['textbook_id'] = 2;
        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.textbook.save',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        return $result['data'];
    }



    /**
     * 课程绑定教材
     * @depends testSaveTextbookApi
     * @param  Textbook $textbook
     */
    public function testCourseBindingTextbook($textbook) {
        $data = ['textbook_ids'=>$textbook['textbook']['id'],'course_id'=>1];
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.textbook.courseBindingTextbook',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
    }





    /**
     * 查看某专业教材的采购情况
     */
    public function testLoadMajorCoursesTextbook() {
        $data = ['major_id'=>40];
        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.textbook.loadMajorTextbook',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS, $result['code']);
        foreach ($result['data']['major_textbook'] as $key => $val) {
            $this->assertArrayHasKey('code', $val);
            $this->assertArrayHasKey('name', $val);
            $this->assertArrayHasKey('year', $val);
            $this->assertArrayHasKey('type', $val);
            $this->assertArrayHasKey('textbook_num', $val);
            $this->assertArrayHasKey('course_textbooks', $val);
            if(!empty($val['course_textbooks'])) {
                foreach ($val['course_textbooks'] as $k => $v) {
                    $this->assertArrayHasKey('textbook', $v);
                    $this->assertArrayHasKey('name', $v['textbook']);
                    $this->assertArrayHasKey('press', $v['textbook']);
                    $this->assertArrayHasKey('author', $v['textbook']);
                    $this->assertArrayHasKey('edition', $v['textbook']);
                    $this->assertArrayHasKey('purchase_price', $v['textbook']);
                    $this->assertArrayHasKey('price', $v['textbook']);
                }
            }

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
            ->get(route('teacher.textbook.list'));
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,$result['code']);
        $this->assertArrayHasKey('textbook', $result['data']);

        foreach ($result['data']['textbook'] as $key => $val) {
            $this->assertArrayHasKey('name', $val);
            $this->assertArrayHasKey('press', $val);
            $this->assertArrayHasKey('author', $val);
            $this->assertArrayHasKey('type', $val);
            $this->assertArrayHasKey('purchase_price', $val);
            $this->assertArrayHasKey('price', $val);
        }

    }


    /**
     * 以班级为单位查询教材使用情况
     */
    public function testLoadGradeCoursesTextbook() {
        $user = $this->getSuperAdmin();
        $data = ['grade_id'=>313];
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.textbook.loadGradeTextbook',$data));
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,$result['code']);
        $this->assertArrayHasKey('textbook', $result['data']);

        if(!empty($result['data']['textbook'])) {
            foreach ($result['data']['textbook'] as $key => $val) {
                $this->assertArrayHasKey('id', $val);
                $this->assertArrayHasKey('code', $val);
                $this->assertArrayHasKey('name', $val);
                $this->assertArrayHasKey('year', $val);
                $this->assertArrayHasKey('term', $val);
                $this->assertArrayHasKey('textbook_num', $val);
                $this->assertArrayHasKey('course_textbooks', $val);

                if(!empty($val['course_textbooks'])) {
                    foreach ($val['course_textbooks'] as $k => $v) {
                        $this->assertArrayHasKey('name', $v['textbook']);
                        $this->assertArrayHasKey('press', $v['textbook']);
                        $this->assertArrayHasKey('author', $v['textbook']);
                        $this->assertArrayHasKey('price', $v['textbook']);
                        $this->assertArrayHasKey('purchase_price', $v['textbook']);
                    }
                }
            }
        }
    }


    /**
     * 加载校区的教材采购情况
     */
    public function testLoadCampusTextbook() {

        $this->withoutExceptionHandling();

        $user = $this->getSuperAdmin();
        $data = ['campus_id'=>1];
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.textbook.loadCampusTextbook',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,$result['code']);
        $this->assertArrayHasKey('campus_textbook', $result['data']);

        if(!empty($result['data']['campus_textbook'])) {
            foreach ($result['data']['campus_textbook'] as  $key => $val) {
                $this->assertArrayHasKey('major_name', $val);
                $this->assertArrayHasKey('name', $val['course']);
                $this->assertArrayHasKey('code', $val['course']);
                $this->assertArrayHasKey('year', $val['course']);
                $this->assertArrayHasKey('type', $val);
                $this->assertArrayHasKey('textbook_num', $val);
                if($val['type'] == 1) {
                     $this->assertArrayHasKey('general_plan_seat', $val['textbook_num']);
                     $this->assertArrayHasKey('self_plan_seat', $val['textbook_num']);
                     $this->assertArrayHasKey('total_plan_seat', $val['textbook_num']);
                     $this->assertArrayHasKey('general_informatics_seat', $val['textbook_num']);
                     $this->assertArrayHasKey('self_informatics_seat', $val['textbook_num']);
                     $this->assertArrayHasKey('total_informatics_seat', $val['textbook_num']);
                }

                if(!empty($val['course']['course_textbooks'])) {
                    foreach ($val['course']['course_textbooks'] as $k => $v){
                        $this->assertArrayHasKey('name', $v['textbook']);
                        $this->assertArrayHasKey('press', $v['textbook']);
                        $this->assertArrayHasKey('author', $v['textbook']);
                        $this->assertArrayHasKey('purchase_price', $v['textbook']);
                        $this->assertArrayHasKey('price', $v['textbook']);
                    }
                }
            }
        }
    }




}
