<?php


namespace Tests\Feature\Event;

use Tests\Feature\BasicPageTestCase;
use App\Utils\Misc\JPushFactory;
use App\Dao\Users\UserDao;

class StudentElectivePushTest extends BasicPageTestCase
{

    /**
     * 测试可以正常push
     */
    public function testItPush()
    {
        $dao = new UserDao;
        $student = $dao->getUserByMobile('1000001');
        $teacher = $dao->getUserByMobile('18601216001');
        $push = JPushFactory::GetInstance();
        $response = $push->send([$student, $teacher], '测试标题', '测试内容', ['newsid' => '123']);

        $this->assertTrue($response->getCode() == 1000);
    }


}
