<?php

namespace Tests\Feature\Notice;

use Tests\Feature\BasicPageTestCase;
use App\Dao\Notice\NoticeDao;
use App\Models\Notices\Notice;

class NoticeDaoTest extends BasicPageTestCase
{

    private function data()
    {
        // 不管哪个类型都是必填
        $data = [
            'school_id'       =>  1,
            'user_id'         =>  1,
            'title'           => 'xxxx', // 标题
            'content'         => 'xxxx', // 内容
            'attachments'        => [['id'=>1,'file_name'=>'','url'=>'']], // 附件ID
            'organization_id' => 1 // 可见范围 部门ID
        ];

        return $data;
    }

    /**
     * 测试正常添加通知
     */
    public function testAddNotice()
    {
        $data = $this->data();
        $data['release_time'] = '2019-10-01';
        $data['type'] = Notice::TYPE_NOTIFY;  // 类型

        $dao = new  NoticeDao;

        $result = $dao->add($data);
        $this->assertTrue($result->getCode() == 1000 );
    }

    /**
     * 测试正常添加公告
     */
    public function testAddAnnouncement()
    {
        $data = $this->data();
        $data['type'] = Notice::TYPE_NOTICE;
        $data['image'] = 1; // 封面ID
        $data['note'] = '备注';
        $dao = new  NoticeDao;

        $result = $dao->add($data);
        $this->assertTrue($result->getCode() == 1000 );
    }

    /**
     * 测试正常添加检查
     */
    public function testAddInspect()
    {
        $data = $this->data();
        $data['inspect_id'] = 1;   // 检查的类型ID
        $data['type'] = Notice::TYPE_INSPECTION;
        $data['image'] = 1;

        $dao = new  NoticeDao;
        $result = $dao->add($data);
        dd($result);
        $this->assertTrue($result->getCode() == 1000 );
    }
}
