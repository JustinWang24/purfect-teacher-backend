<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午3:11
 */
namespace App\Http\Controllers\Teacher;


use App\Utils\JsonBuilder;
use App\Dao\Schools\RoomDao;
use App\Models\Schools\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Dao\Teachers\ConferenceDao;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Dao\Teachers\ConferenceUserDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Http\Requests\Teacher\ConferenceRequest;

class ConferenceController extends Controller
{
    public function index()
    {
        return view('teacher.conference.index', $this->dataForView);
    }


    public function data(ConferenceRequest $request)
    {
        #判断权限
//        $schoolId = $request->session()->get('school.id');
        $userId = Auth::id();
        $dao = new ConferenceDao();
        $map = ['user_id'=>$userId];
        $list = $dao->getList($map)->toArray();

        echo json_encode(['code'=>0,"count" => 1, "data" => $list]);die;

    }


    /**
     * 添加页面
     * @param ConferenceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(ConferenceRequest $request)
    {

        $roomDao = new RoomDao($request->user());
        $schoolId = 1;

        #会议室
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_MEETING_ROOM];
        $room = $roomDao->getRooms($map)->toArray();
        #老师
        $map = ['school_id'=>$schoolId];
        $teacherDao = new TeacherProfileDao();
        $field = ['id','teacher_id','name','school_id'];
        $teacherList = $teacherDao->getTeachers($map, $field)->toArray();

        $this->dataForView['room'] = $room;
        $this->dataForView['teacher'] = $teacherList;
        return view('teacher.conference.add', $this->dataForView);
    }


    /**
     * 插入数据
     * @param ConferenceRequest $request
     * @return string
     */
    public function create(ConferenceRequest $request)
    {
        $conferenceData = $request->all();
//        dd($conferenceData);
        $conferenceData['school_id'] = $request->session()->get('school.id');


        $dao = new ConferenceDao();
        $conferenceUserDao = new ConferenceUserDao();

        try{
            DB::beginTransaction();
            $s1 = $dao->addConference($conferenceData);

            if(!$s1->id)
            {
                throw new \Exception('创建会议失败');
            }

            foreach ($conferenceData['participant'] as $key => $val)
            {
                $conferenceUserData = [
                    'conference_id' => $s1->id,
                    'user_id'       => $val,
                    'school_id'     => $conferenceData['school_id'],
                    'statue'        => 0,
                    'from'          => $conferenceData['from'],
                    'to'            => $conferenceData['to'],
                ];
                $s2 = $conferenceUserDao->addConferenceUser($conferenceUserData);
                if(!$s2->id)
                {
                    throw new \Exception('邀请人添加失败');
                }
            }

            DB::commit();
            return JsonBuilder::Success('创建成功');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $msg = $e->getMessage();
            return JsonBuilder::Error($msg);
        }

    }




    /**
     * 获取参会人员  status 0:没有会议  1:有会议
     * @param ConferenceRequest $request
     * @return string
     */
    public function getUsers(ConferenceRequest $request)
    {

        $from = $request->get('from');
        $to = $request->get('to');

        $schoolId = 1;
        $userId = Auth::id();
        $map = [['school_id', '=', $schoolId],['teacher_id', '!=', $userId]];
        $teacherDao = new TeacherProfileDao();
        $field = ['id','teacher_id','name','school_id'];
        $teacherList = $teacherDao->getTeachers($map, $field)->toArray();


        #查询老师在当前时间是否有空
        $conferenceUserDao = new ConferenceUserDao();

        $conferenceUsers = $conferenceUserDao->getConferenceUser($from, $to, $schoolId);

        $userIdArr = array_column($conferenceUsers,'user_id');
        foreach ($teacherList as $key => $value)
        {
            if(in_array($value['teacher_id'],$userIdArr))
            {
                $teacherList[$key]['status'] = 1; //有会议
            }
            else
            {
                $teacherList[$key]['status'] = 0; //没有会议
            }
        }

        return JsonBuilder::Success($teacherList);

    }


    /**
     * 获取会议室
     * @param Request $request
     * @return string
     */
    public function getRooms(Request $request)
    {
        $roomDao = new RoomDao($request->user());
        $schoolId = 50;
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_MEETING_ROOM];
        $list = $roomDao->getRooms($map)->toArray();
        return JsonBuilder::Success($list);
    }

}