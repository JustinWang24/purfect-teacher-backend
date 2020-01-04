<?php

namespace App\Http\Controllers\Teacher;


use App\Dao\Users\UserDao;
use App\Utils\JsonBuilder;
use App\Dao\Schools\RoomDao;
use App\Models\Schools\Room;
use App\Utils\FlashMessageBuilder;
use App\Models\Teachers\Conference;
use App\Dao\Teachers\ConferenceDao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Dao\Teachers\TeacherProfileDao;
use App\Http\Requests\Teacher\ConferenceRequest;

class ConferenceController extends Controller
{
    /**
     * 会议列表
     * @param ConferenceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ConferenceRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new ConferenceDao();
        $list = $dao->getConferenceBySchoolId($schoolId);
        $this->dataForView['list'] = $list;
        return view('teacher.conference.index', $this->dataForView);
    }


    /**
     * 添加页面
     * @param ConferenceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ConferenceRequest $request) {
        $user = $request->user();
        if($request->isMethod('post')) {

            $data = $request->getFormData();
            // 管理员直接通过
            if($user->isSchoolAdminOrAbove()) {
                $data['status'] = Conference::STATUS_CHECK;
            }
            $conferenceDao = new ConferenceDao();
            $result = $conferenceDao->addConference($data);
            $msg = $result->getMessage();
            if ($result->isSuccess()) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $msg);
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $msg);
            }
            return redirect()->route('teacher.conference.index');

        }

        $conference = new Conference();

        // 权限不同 申请会议类型不同
        if($user->isSchoolAdminOrAbove()) {
            $type = $conference->allType();
        } else {
            $type = [Conference::TYPE_INTERIOR=>Conference::TYPE_INTERIOR_TEXT];
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

        $this->dataForView['room'] = $room;
        $this->dataForView['teacher'] = $teacherList;
        $this->dataForView['type'] = $type;
        return view('teacher.conference.add', $this->dataForView);
    }


    /**
     * 编辑会议
     * @param ConferenceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(ConferenceRequest $request) {
        $dao = new ConferenceDao();
        $user = $request->user();
        if($request->isMethod('post')) {
            $data = $request->getFormData();
            // 管理员直接通过
            if($user->isSchoolAdminOrAbove()) {
                $data['status'] = Conference::STATUS_CHECK;
            }
            $result = $dao->updConference($data);
            $msg = $result->getMessage();
            if ($result->isSuccess()) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $msg);
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $msg);
            }
            return redirect()->route('teacher.conference.index');
        }

        $id = $request->get('id');
        $conference = $dao->getConferenceById($id);
        $from = Carbon::parse($conference->from);
        $to = Carbon::parse($conference->to);
        $conference->from = $from->format('Y-m-d').'T'.$from->format('H:i:s');
        $conference->to = $to->format('Y-m-d').'T'.$to->format('H:i:s');

        // 权限不同 申请会议类型不同
        if($user->isSchoolAdminOrAbove()) {
            $type = $conference->allType();
        } else {
            $type = [Conference::TYPE_INTERIOR=>Conference::TYPE_INTERIOR_TEXT];
        }

        // 会议室
        $schoolId = $request->getSchoolId();
        $roomDao = new RoomDao($request->user());
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_MEETING_ROOM];
        $room = $roomDao->getRooms($map);
        $this->dataForView['room'] = $room;
        $this->dataForView['conference'] = $conference;
        $this->dataForView['type'] = $type;
        $this->dataForView['user'] = $user;
        return view('teacher.conference.edit', $this->dataForView);
    }


    /**
     * 删除会议
     * @param ConferenceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(ConferenceRequest $request) {
        $id = $request->get('id');
        $dao = new ConferenceDao();
        $result = $dao->deleteConference($id);
        $msg = $result->getMessage();
        if ($result->isSuccess()) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $msg);
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $msg);
        }
        return redirect()->route('teacher.conference.index');
    }

}
