<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 2:42 PM
 */

namespace Tests\Feature\CampusPages;
use Tests\Feature\BasicPageTestCase;

class RoomTest extends BasicPageTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 测试在房间列表的页面上, 可以看到
     */
    public function testItCanSeeRoomsListInBuildingView(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.building.rooms',['uuid'=>1]));

        $response->assertSee('id="btn-create-room-from-building"');
        $response->assertSee('btn-edit-room'); // 编辑房间的按钮
        $response->assertSee('btn-need-confirm'); // 删除的按钮
    }

    /**
     * 测试在添加房间的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInRoomCreatePage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.room.add',['uuid'=>1]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="add-room-form"');
        $response->assertSee('id="room-name-input"');
        $response->assertSee('id="room-desc-input"');
        $response->assertSee('id="room-type-input"');
        $response->assertSee('id="btn-create-room"');
        $response->assertSee('link-return'); // 返回按钮

        $response->assertSee('id="existed-rooms-view"');
        $response->assertSee('已创建的房间');
    }

    /**
     * 测试在修改房间的页面上, 完整的表单
     */
    public function testItCanSeeRightFormInRoomEditPage(){
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 50)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.room.edit',['uuid'=>2]));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="edit-room-form"');
        $response->assertSee('id="room-name-input"');
        $response->assertSee('id="room-desc-input"');
        $response->assertSee('id="room-type-input"');
        $response->assertSee('id="btn-save-room"');
        $response->assertSee('link-return'); // 返回按钮

        $response->assertSee('id="existed-rooms-view"');
        $response->assertSee('其他已创建的房间');
    }
}