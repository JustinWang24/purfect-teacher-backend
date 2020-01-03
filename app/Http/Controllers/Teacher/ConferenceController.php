<?php

namespace App\Http\Controllers\Teacher;


use App\Dao\Users\UserDao;
use App\Utils\JsonBuilder;
use App\Dao\Schools\RoomDao;
use App\Models\Schools\Room;
use App\Utils\FlashMessageBuilder;
use App\Models\Teachers\Conference;
use App\Dao\Teachers\ConferenceDao;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Dao\Teachers\TeacherProfileDao;
use App\Http\Requests\Teacher\ConferenceRequest;

class ConferenceController extends Controller
{
    public function index(ConferenceRequest $request) {
        $schoolId = $request->getSchoolId();
        $userId = $request->user()->id;
        $dao = new ConferenceDao();
        $list = $dao->getConferenceListByUser($userId,$schoolId);
        $this->dataForView['list'] = $list;
        return view('teacher.conference.index', $this->dataForView);
    }


    /**
     * 添加页面
     * @param ConferenceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ConferenceRequest $request) {
        if($request->isMethod('post')) {
            $data = $request->getFormData();
            dd($data);
            $conferenceDao = new ConferenceDao();
            $result = $conferenceDao->addConferenceFlow($data);
            $msg = $result->getMessage();
            if ($result->isSuccess()) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $msg);
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $msg);
            }
            return redirect()->route('teacher.conference.index');

        }
        $roomDao = new RoomDao($request->user());
        $schoolId = $request->getSchoolId();
        // 会议室
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_MEETING_ROOM];
        $field = ['id', 'school_id', 'name'];
        $room = $roomDao->getRooms($map,$field);
        // 老师
        $userDao = new UserDao();
        $teacherList = $userDao->getTeachersBySchool($schoolId, true);
        $conference = new Conference();
        $this->dataForView['room'] = $room;
        $this->dataForView['teacher'] = $teacherList;
        $this->dataForView['type'] = $conference->allType();
        return view('teacher.conference.add', $this->dataForView);
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
//        $field = ['id','user_id','school_id'];
        $teacherList = $teacherDao->getTeachers($map)->toArray();

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
