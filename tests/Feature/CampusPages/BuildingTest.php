<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 11:29 AM
 */

namespace Tests\Feature\CampusPages;
use Tests\Feature\BasicPageTestCase;
use App\Models\Schools\Building;

class BuildingTest extends BasicPageTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 测试在建筑物列表的页面上, 可以看到
     */
    public function testItCanSeeBuildingsListInAnyCampusView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.campus.buildings',['uuid'=>'251','by'=>'campus','type'=>Building::TYPE_CLASSROOM_BUILDING]));

        $response->assertSee('id="btn-create-building-from-campus"');
        $response->assertSee('class="anchor-rooms-counter"');
        $response->assertSee('btn-edit-building'); // 编辑校园的按钮
    }

    /**
     * 测试在添加建筑物的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInBuildingCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.building.add',['uuid'=>251]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-building-form"');
        $response->assertSee('id="building-name-input"');
        $response->assertSee('id="building-desc-input"');
        $response->assertSee('id="btn-create-building"');
        $response->assertSee('link-return'); // 返回按钮
    }

    /**
     * 测试在添加学校的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInBuildingEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.building.edit',['uuid'=>1]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-building-form"');
        $response->assertSee('id="building-name-input"');
        $response->assertSee('id="building-desc-input"');
        $response->assertSee('id="btn-save-building"');
        $response->assertSee('link-return'); // 返回按钮
    }
}