<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/24
 * Time: 下午2:31
 */
namespace Tests\Feature\TeacherPages;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\Feature\BasicPageTestCase;

class ConferenceTest extends BasicPageTestCase
{


    public function setUp(): void
    {
        parent::setUp();
    }


    /**
     * 测试创建会议页面
     */
    public function testAddConferencePage()
    {
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.add',['uuid'=>'6fec3fe9-da7a-44a2-9ce1-1a541e931bec']));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="conference-title-input"');              //会议主题
        $response->assertSee('id="conference-room_id-input"');            //会议地点
        $response->assertSee('id="conference-from-input"');               //会议开始时间
        $response->assertSee('id="conference-to-input"');                 //会议结束时间
        $response->assertSee('id="conference-user_id-input"');            //会议负责人
        $response->assertSee('id="conference-participant-select-array"'); //会议参会人
        $response->assertSee('id="conference-sign_out-radio"');           //会议结束签退
        $response->assertSee('id="conference-video-radio"');              //视频会议
        $response->assertSee('id="conference-remark-text"');              //特殊说明
        $response->assertSee('id="conference-file-file"');                //附件
    }


    /**
     * 获取参会人员接口
     */
    public function testGetUserApi()
    {
        $from = Carbon::now()->format('Y-m-d');
        $to   = Carbon::tomorrow()->format('Y-m-d');

        $data = ['from'=>$from,'to'=>$to];
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.getUser',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('error_no', $result);
        $this->assertArrayHasKey('data', $result);
        foreach ($result['data'] as $key => $val)
        {
            $this->assertArrayHasKey('id', $val);
            $this->assertArrayHasKey('teacher_id', $val);
            $this->assertArrayHasKey('name', $val);
            $this->assertArrayHasKey('school_id', $val);
            $this->assertArrayHasKey('status', $val);
        }
    }


    /**
     * 获取会议室接口
     */
    public function testGetRoomsApi()
    {
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.getRooms'));
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('error_no', $result);
        $this->assertArrayHasKey('data', $result);
        foreach ($result['data'] as $key => $val)
        {
            $this->assertArrayHasKey('id', $val);
            $this->assertArrayHasKey('school_id', $val);
            $this->assertArrayHasKey('name', $val);

        }
    }


    /**
     * 创建会议接口
     */
    public function testAddConferenceApi()
    {

        $data = $this->__createData();

        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.conference.create',$data));
        dd($response->content());



    }


    public function __createData()
    {
        $data = [
            'title'      => Str::random(10),
            'room_id'    => 1,
            'from'       => Carbon::now()->format('Y-m-d H:i:s'),
            'to'         => Carbon::tomorrow()->format('Y-m-d 00:00:00'),
            'user_id'    => 1,
            'participant'=> [11,12,13],
            'sign_out'   => 0,
            'remark'     => '',

        ];
        return  $data;
    }

}

