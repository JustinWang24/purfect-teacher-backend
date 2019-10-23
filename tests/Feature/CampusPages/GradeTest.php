<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 6:11 PM
 */

namespace Tests\Feature\CampusPages;

use Tests\Feature\BasicPageTestCase;

class GradeTest extends BasicPageTestCase
{
    /**
     * 测试在专业列表的页面上, 可以看到该学院的专业列表
     */
    public function testItCanSeeGradesListInAnyMajorView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.major.grades',['by'=>'department','uuid'=>2953]));

        $response->assertSee('id="btn-create-brade-from-major"');
        $response->assertSee('class="students-counter"');
        $response->assertSee('btn-edit-grade'); // 编辑系的按钮
        $response->assertSee(route('school_manager.grade.edit')); // 编辑学院的系的链接地址检查
        $response->assertSee(route('school_manager.grade.add')); // 编辑学院的系的链接地址检查
    }

    /**
     * 测试在修改系的页面上, 完整的表单
     */
    public function testItCanSeeRightFormGradeEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.grade.edit',['uuid'=>24000]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-grade-form"');
        $response->assertSee('id="grade-id-input"');
        $response->assertSee('id="grade-major-id-input"'); // 需要这个字段, 用来在成功之后的页面跳转
        $response->assertSee('id="grade-name-input"');
        $response->assertSee('id="grade-desc-input"');
        $response->assertSee('id="grade-year-input"');
        $response->assertSee('id="btn-save-grade"');
        $response->assertSee('link-return'); // 返回按钮
    }

    /**
     * 测试在添加系的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInGradeCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.grade.add',['uuid'=>2953]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-grade-form"');
        $response->assertSee('id="school-id-input"');
        $response->assertSee('id="major-id-input"');
        $response->assertSee('id="grade-year-input"');
        $response->assertSee('id="grade-name-input"');
        $response->assertSee('id="grade-desc-input"');
        $response->assertSee('id="btn-save-grade"');
        $response->assertSee('link-return'); // 返回按钮
    }
}