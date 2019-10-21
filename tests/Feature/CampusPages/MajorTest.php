<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 6:11 PM
 */

namespace Tests\Feature\CampusPages;

use Tests\Feature\BasicPageTestCase;

class MajorTest extends BasicPageTestCase
{
    /**
     * 测试在专业列表的页面上, 可以看到该学院的专业列表
     */
    public function testItCanSeeMajorsListInAnyDepartmentView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.department.majors',['by'=>'department','uuid'=>1477]));

        $response->assertSee('id="btn-create-major-from-department"');
        $response->assertSee('class="anchor-grades-counter"');
        $response->assertSee('class="employees-counter"');
        $response->assertSee('class="students-counter"');
        $response->assertSee('btn-edit-major'); // 编辑系的按钮
        $response->assertSee(route('school_manager.major.edit')); // 编辑学院的系的链接地址检查
    }

    /**
     * 测试在修改系的页面上, 完整的表单
     */
    public function testItCanSeeRightFormMajorEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.major.edit',['uuid'=>2953]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-major-form"');
        $response->assertSee('id="major-id-input"');
        $response->assertSee('id="major-department-id-input"'); // 需要这个字段, 用来在成功之后的页面跳转
        $response->assertSee('id="major-name-input"');
        $response->assertSee('id="major-desc-input"');
        $response->assertSee('id="btn-save-major"');
        $response->assertSee('link-return'); // 返回按钮
    }

    /**
     * 测试在添加系的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInMajorCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.major.add',['uuid'=>1477]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-major-form"');
        $response->assertSee('id="school-id-input"');
        $response->assertSee('id="department-id-input"');
        $response->assertSee('id="major-name-input"');
        $response->assertSee('id="major-desc-input"');
        $response->assertSee('id="btn-save-major"');
        $response->assertSee('link-return'); // 返回按钮
    }
}