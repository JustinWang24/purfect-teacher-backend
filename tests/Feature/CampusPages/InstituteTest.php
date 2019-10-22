<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 6:11 PM
 */

namespace Tests\Feature\CampusPages;

use Tests\Feature\BasicPageTestCase;

class InstituteTest extends BasicPageTestCase
{
    /**
     * 测试在校区列表的页面上, 可以看到该校区的学院列表
     */
    public function testItCanSeeInstitutesListInAnyCampusView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.campus.institutes',['by'=>'campus','uuid'=>247]));

        $response->assertSee('id="btn-create-institute-from-campus"');
        $response->assertSee('class="anchor-department-counter"');
        $response->assertSee('class="employees-counter"');
        $response->assertSee('class="students-counter"');
        $response->assertSee('btn-edit-institute'); // 编辑校园的按钮
        $response->assertSee(route('school_manager.institute.edit')); // 编辑学院的按钮的链接地址检查
    }

    /**
     * 测试在修改学院的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInInstituteEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.institute.edit',['uuid'=>493]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-institute-form"');
        $response->assertSee('id="institute-id-input"');
        $response->assertSee('id="institute-campus-id-input"'); // 需要这个字段, 用来在成功之后的页面跳转
        $response->assertSee('id="institute-name-input"');
        $response->assertSee('id="institute-desc-input"');
        $response->assertSee('id="btn-save-institute"');
        $response->assertSee('link-return'); // 返回按钮
    }

    /**
     * 测试在添加学院的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInInstituteCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.institute.add',['uuid'=>247]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-institute-form"');
        $response->assertSee('id="school-id-input"');
        $response->assertSee('id="campus-id-input"');
        $response->assertSee('id="institute-name-input"');
        $response->assertSee('id="institute-desc-input"');
        $response->assertSee('id="btn-save-institute"');
        $response->assertSee('link-return'); // 返回按钮
    }
}