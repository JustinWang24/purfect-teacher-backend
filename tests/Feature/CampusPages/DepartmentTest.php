<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 6:11 PM
 */

namespace Tests\Feature\CampusPages;

use Tests\Feature\BasicPageTestCase;

class DepartmentTestTest extends BasicPageTestCase
{
    /**
     * 测试在学院列表的页面上, 可以看到该学院的系列表
     */
    public function testItCanSeeDepartmentsListInAnyInstituteView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.institute.departments',['by'=>'institute','uuid'=>493]));

        $response->assertSee('id="btn-create-department-from-institute"');
        $response->assertSee('class="anchor-majors-counter"');
        $response->assertSee('class="employees-counter"');
        $response->assertSee('class="students-counter"');
        $response->assertSee('btn-edit-major'); // 编辑系的按钮
        $response->assertSee(route('school_manager.department.edit')); // 编辑学院的系的链接地址检查
    }

    /**
     * 测试在修改系的页面上, 完整的表单
     */
    public function testItCanSeeRightFormDepartmentEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.department.edit',['uuid'=>1477]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-department-form"');
        $response->assertSee('id="department-id-input"');
        $response->assertSee('id="department-institute-id-input"'); // 需要这个字段, 用来在成功之后的页面跳转
        $response->assertSee('id="department-name-input"');
        $response->assertSee('id="department-desc-input"');
        $response->assertSee('id="btn-save-department"');
        $response->assertSee('link-return'); // 返回按钮
    }

    /**
     * 测试在添加系的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInDepartmentCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.department.add',['uuid'=>493]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-department-form"');
        $response->assertSee('id="school-id-input"');
        $response->assertSee('id="institute-id-input"');
        $response->assertSee('id="department-name-input"');
        $response->assertSee('id="department-desc-input"');
        $response->assertSee('id="btn-save-department"');
        $response->assertSee('link-return'); // 返回按钮
    }
}