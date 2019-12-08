<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 5/12/19
 * Time: 1:10 PM
 */

namespace Tests\Unit\Pipeline;
use App\Dao\Pipeline\ActionDao;
use App\Models\NetworkDisk\Media;
use App\Models\Pipeline\Flow\Handler;
use App\User;
use Tests\TestCase;

class ActionDaoTest extends TestCase
{
    public function testCanCreateActionWithAttachments(){
        $dao = new ActionDao();
        $action = $dao->create($this->mockActionData());

        $this->assertNotNull($action);

        $this->assertGreaterThan(0, count($action->attachments));

        $this->assertTrue($dao->delete($action->id));

        // 测试手机的提交

        $action = $dao->create($this->mockActionDataForApp());
        $this->assertNotNull($action);

        $this->assertGreaterThan(0, count($action->attachments));

        $this->assertTrue($dao->delete($action->id));
    }

    public function testCanGetNoticeToByHandler(){
        $handler = Handler::find(28);
        $user = User::find(5);
        if($handler && $user){
            $users = $handler->getNoticeTo($user);
            $this->assertTrue(count($users) > 0);
        }
    }

    private function mockActionData(){
        return [
            'flow_id'=>1,
            'node_id'=>1,
            'user_id'=>1,
            'result'=>1,
            'content'=>"xxxx",
            'attachments'=>[
                [
                    'file_name'=>'111',
                    'url'=>'222',
                    'media_id'=>1
                ],[
                    'file_name'=>'111',
                    'url'=>'222',
                    'media_id'=>2
                ]
            ]
        ];
    }

    private function mockActionDataForApp(){
        $medias = Media::orderBy('created_at','asc')->take(2)->get();

        return [
            'flow_id'=>1,
            'node_id'=>1,
            'user_id'=>1,
            'result'=>1,
            'content'=>"xxxx",
            'attachments'=>[
                $medias[0]->id, $medias[1]->id
            ]
        ];
    }
}