<?php

namespace Tests\Feature\FacilityManager;

use Tests\Feature\BasicPageTestCase;

class FacilityTest extends BasicPageTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    /**
     * 列表
     */
    public function testListPage() {

        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.facility.list'));

         $response->assertSee('btn-edit-facility');
         $response->assertSee('btn-delete-facility btn-need-confirm');

    }




    /**
     * 添加设备
     */
    public function testAddFacilityPage() {

        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.facility.add'));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="facility-number-input"');
        $response->assertSee('id="facility-name-input"');
        $response->assertSee('id="facility-type-select"');
        $response->assertSee('id="facility-campus-select"');
        $response->assertSee('id="facility-building-select"');
        $response->assertSee('id="facility-room-select"');
        $response->assertSee('id="facility-addr-input"');
        $response->assertSee('id="btn-create-facility"');
        $response->assertSee('link-return"');
    }


    /**
     * 编辑设备
     */
    public function testEditFacilityPage() {

        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.facility.edit'));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="facility-number-input"');
        $response->assertSee('id="facility-name-input"');
        $response->assertSee('id="facility-type-select"');
        $response->assertSee('id="facility-campus-select"');
        $response->assertSee('id="facility-building-select"');
        $response->assertSee('id="facility-room-select"');
        $response->assertSee('id="facility-addr-input"');
        $response->assertSee('id="facility-addr-input"');
        $response->assertSee('id="facility-status-radio-close"');
        $response->assertSee('id="facility-status-radio-open"');
        $response->assertSee('id="btn-edit-facility"');
        $response->assertSee('link-return"');
    }


    /**
     * 获取建筑接口
     */
    public function testBuildingListApi() {
        $data = ['campus_id'=>246];
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.facility.getBuildingList',$data));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        if(!empty($result['data'])) {
            $this->assertArrayHasKey('data', $result);
            $this->assertArrayHasKey('building', $result['data']);
            foreach ($result['data']['building'] as $key => $val) {
                $this->assertArrayHasKey('id', $val);
                $this->assertArrayHasKey('name', $val);
            }
        }
    }


    /**
     * 获取教室接口
     */
    public function testRoomListApi() {
        $data = ['building_id'=>1];
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.facility.getRoomList',$data));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        if(!empty($result['data'])) {
            $this->assertArrayHasKey('data', $result);
            $this->assertArrayHasKey('room', $result['data']);
            foreach ($result['data']['room'] as $key => $val) {
                $this->assertArrayHasKey('id', $val);
                $this->assertArrayHasKey('name', $val);
            }
        }
    }


}
