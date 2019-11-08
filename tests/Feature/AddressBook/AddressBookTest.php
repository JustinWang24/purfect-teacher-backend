<?php


namespace Tests\Feature\AddressBook;

use Tests\Feature\BasicPageTestCase;

class AddressBookTest extends BasicPageTestCase
{

    /**
     * 测试获取班级通讯录接口
     */
    public function testItCanGetAddressBookApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['uuid' => '2ecbd004-dd1e-4a93-bb6c-938b393c251c'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.address.book.class'), $data);

        $data = json_decode($response->content(), true);

        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertArrayHasKey('schoolmate_list', $data['data'], '返回数据中必须有 schoolmate_list 字段');
        $this->assertArrayHasKey('teacher_list', $data['data'], '返回数据中必须有 teacher_list 字段');
        if (is_array($data['data']['schoolmate_list']) && !empty($data['data']['schoolmate_list'])) {
            foreach ($data['data']['schoolmate_list'] as $key => $val) {
                $this->assertArrayHasKey('name', $val, '返回数据中必须有 name 字段');
                $this->assertArrayHasKey('tel', $val, '返回数据中必须有 tel 字段');
                $this->assertArrayHasKey('type', $val, '返回数据中必须有 type 字段');
            }
        } else {
            $this->assertIsArray($data['data']['schoolmate_list']);
        }

        if (is_array($data['data']['teacher_list']) && !empty($data['data']['teacher_list'])) {
            foreach ($data['data']['teacher_list'] as $key => $val) {
                $this->assertArrayHasKey('name', $val, '返回数据中必须有 name 字段');
                $this->assertArrayHasKey('tel', $val, '返回数据中必须有 tel 字段');
                $this->assertArrayHasKey('type', $val, '返回数据中必须有 type 字段');
            }
        } else {
            $this->assertIsArray($data['data']['teacher_list']);
        }


    }

    /**
     * 测试获取学校部门通讯录接口
     */
    public function testItCanGetOfficialApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['uuid' => '2ecbd004-dd1e-4a93-bb6c-938b393c251c'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.address.book.official'), $data);

        $data = json_decode($response->content(), true);

        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertArrayHasKey('department_list', $data['data'], '返回数据中必须有 schoolmate_list 字段');
        if (is_array($data['data']['department_list']) && !empty($data['data']['department_list'])) {
            foreach ($data['data']['department_list'] as $key => $val) {
                $this->assertArrayHasKey('name', $val, '返回数据中必须有 name 字段');
                $this->assertArrayHasKey('tel', $val, '返回数据中必须有 tel 字段');
            }
        } else {
            $this->assertIsArray($data['data']['department_list']);
        }
    }

}
