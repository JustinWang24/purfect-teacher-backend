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
        $this->withoutExceptionHandling();
        $user = $this->getTeacher();

        $response = $this->setSchoolAsUser($user, 1)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.add',['uuid'=>'6fec3fe9-da7a-44a2-9ce1-1a541e931bec']));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="conference-title-input"');              //会议主题
        $response->assertSee('id="conference-room_id-input"');            //会议地点
        $response->assertSee('id="conference-date-input-date"');          //会议当天时间
        $response->assertSee('id="conference-from-input-time"');          //会议开始时间
        $response->assertSee('id="conference-to-input-time"');            //会议结束时间
        $response->assertSee('id="conference-user_id-input"');            //会议负责人
        $response->assertSee('id="conference-participant-select-array"'); //会议参会人
        $response->assertSee('id="conference-sign_out-radio"');           //会议结束签退
        $response->assertSee('id="conference-video-radio"');              //视频会议
        $response->assertSee('id="conference-remark-text"');              //特殊说明
        $response->assertSee('id="conference-media_id-file"');            //附件
    }


    /**
     * 获取参会人员接口
     */
    public function testGetUserApi()
    {
        $from = Carbon::now()->format('Y-m-d');
        $to   = Carbon::tomorrow()->format('Y-m-d');

        $data = ['from'=>$from,'to'=>$to];
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.getUsers',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data']['teacher'] as $key => $val)
        {
            $this->assertArrayHasKey('id', $val);
            $this->assertArrayHasKey('user_id', $val);
            $this->assertArrayHasKey('name', $val['users']);
            $this->assertArrayHasKey('school_id', $val);
            $this->assertArrayHasKey('status', $val);
        }
    }


    /**
     * 获取会议室接口
     */
    public function testGetRoomsApi()
    {
        $user = $this->getTeacher();
        $date = Carbon::now()->format('Y-m-d');
        $get = ['date'=>$date];

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.getRooms',$get));
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        $this->assertArrayHasKey('data', $result);
        foreach ($result['data']['room'] as $key => $val)
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
        $this->withoutExceptionHandling();
        $data = $this->__createConferenceData();

        $user = $this->getTeacher();

        $response = $this->setSchoolAsUser($user, 1)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.conference.create',$data));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }


    /**
     * 获取会议列表接口
     */
    public function testGetConferenceListApi()
    {
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.conference.data'));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        $this->assertArrayHasKey('conference',$result['data']);
    }


    /**
     * 会议数据
     * @return array
     */
    public function __createConferenceData()
    {
        $data = [
            'title'      => Str::random(10),
            'room_id'    => 1,
            'date'       => Carbon::now()->format('Y-m-d'),
            'from'       => Carbon::now()->format('H:i:s'),
            'to'         => Carbon::parse('+5 minutes ')->format('H:i:s'),
            'user_id'    => 1,
            'participant'=> [11,12,13],
            'sign_out'   => 0,
            'video'      => 0,
            'remark'     => '',
            'media_id'   => 1,

        ];
        return  $data;
    }




}

