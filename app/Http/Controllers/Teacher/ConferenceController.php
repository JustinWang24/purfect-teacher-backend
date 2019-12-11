<?php

namespace App\Http\Controllers\Teacher;


use App\Utils\JsonBuilder;
use App\Dao\Schools\RoomDao;
use App\Models\Schools\Room;
use App\Dao\Teachers\ConferenceDao;
use App\Dao\Teachers\TeacherProfileDao;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ConferenceRequest;

class ConferenceController extends Controller
{
    public function index() {
        return view('teacher.conference.index', $this->dataForView);
    }


    public function data(ConferenceRequest $request) {
        #判断权限
        $schoolId = $request->getSchoolId();
        $user = $request->user();
        $userId = Auth::id();
        $dao = new ConferenceDao();
        $map = ['user_id'=>$userId];
        $list = $dao->getConferenceListByUser($user, $map,$schoolId)->toArray();
        $result = ['conference'=>$list];
        return JsonBuilder::Success($result);

    }


    /**
     * 添加页面
     * @param ConferenceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(ConferenceRequest $request) {
        $roomDao = new RoomDao($request->user());
        $schoolId = $request->getSchoolId();

        #会议室
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_MEETING_ROOM];
        $field = ['id', 'school_id', 'name'];
        $room = $roomDao->getRooms($map,$field);
        #老师
        $map = ['school_id'=>$schoolId];
        $teacherDao = new TeacherProfileDao();
        $field = ['id','teacher_id','name','school_id'];
        $teacherList = $teacherDao->getTeachers($map, $field);

        $this->dataForView['room'] = $room;
        $this->dataForView['teacher'] = $teacherList;
        return view('teacher.conference.add', $this->dataForView);
    }


    /**
     * 插入数据
     * @param ConferenceRequest $request
     * @return string
     */
    public function create(ConferenceRequest $request) {
        $conferenceData = $request->all();
        $conferenceData['school_id'] = $request->getSchoolId();
        $conferenceDao = new ConferenceDao();
        $return = $conferenceDao->addConferenceFlow($conferenceData);
        if($return->isSuccess()) {
            return JsonBuilder::Success($return->getMessage());
        } else {
            return JsonBuilder::Error($return->getMessage());
        }

    }




    /**
     * 获取参会人员  status 0:没有会议  1:有会议
     * @param ConferenceRequest $request
     * @return string
     */
    public function getUsers(ConferenceRequest $request) {

        $from = $request->get('from');
        $to = $request->get('to');

        $schoolId = $request->getSchoolId();
        $userId = Auth::id();
        $map = [['school_id', '=', $schoolId],['user_id', '!=', $userId]];
        $teacherDao = new TeacherProfileDao();
        $field = ['id','user_id','school_id'];
        $teacherList = $teacherDao->getTeachers($map, $field)->toArray();

        #查询老师在当前时间是否有空
        $conferenceDao = new ConferenceDao();
        $conferenceUsers = $conferenceDao->getConferenceUser($from, $to, $schoolId);

        $userIdArr = array_column($conferenceUsers,'user_id');
        foreach ($teacherList as $key => $value) {
            if(in_array($value['user_id'],$userIdArr)) {
                $teacherList[$key]['status'] = 1; //有会议
            } else {
                $teacherList[$key]['status'] = 0; //没有会议
            }
        }

        $result = ['teacher'=>$teacherList];

        return JsonBuilder::Success($result);

    }



    /**
     * 获取会议室 把当前已预约的时间返回
     * @param ConferenceRequest $request
     * @return string
     */
    public function getRooms(ConferenceRequest $request) {
        $date = $request->get('date');

        $roomDao = new RoomDao($request->user());
        $schoolId = $request->getSchoolId();
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_MEETING_ROOM];
        $field = ['id', 'school_id', 'name'];
        $list = $roomDao->getRooms($map, $field)->toArray();

        //查询开会的房间的时间
        $conferenceDao = new ConferenceDao();
        $map = ['date'=>$date,'school_id'=>$schoolId];

        $field = ['from','to','room_id'];
        $conferenceList = $conferenceDao->getConference($map,$field,'room_id')->toArray();

        foreach ($list as $key => $val) {
            if(array_key_exists($val['id'],$conferenceList)) {
                $list[$key]['time'] = $conferenceList[$val['id']];
            } else {
                $list[$key]['time'] = [];
            }
        }
        $result = ['room'=>$list];
        return JsonBuilder::Success($result);
    }

}
