<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 6:44 PM
 */

namespace Tests\Feature\CampusPages;
use Tests\Feature\BasicPageTestCase;

class CampusTest extends BasicPageTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 测试在学校的页面上, 可以看到该学校的校区列表
     */
    public function testItCanSeeCampusListInAnySchoolView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.school.view'));

        $response->assertSee('id="btn-create-campus-from-school"');
        $response->assertSee('class="anchor-institute-counter"');
        $response->assertSee('class="employees-counter"');
        $response->assertSee('class="students-counter"');
        $response->assertSee('btn-edit-campus'); // 编辑校园的按钮
    }

    /**
     * 测试在添加学校的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInCampusCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.campus.add'));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-campus-form"');
        $response->assertSee('id="campus-name-input"');
        $response->assertSee('id="campus-desc-input"');
        $response->assertSee('id="btn-create-campus"');
        $response->assertSee('link-return'); // 返回按钮
    }

    /**
     * 测试在添加学校的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInCampusEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.campus.edit',['uuid'=>247]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-campus-form"');
        $response->assertSee('id="campus-name-input"');
        $response->assertSee('id="campus-desc-input"');
        $response->assertSee('id="btn-create-campus"');
        $response->assertSee('link-return'); // 返回按钮
    }
}