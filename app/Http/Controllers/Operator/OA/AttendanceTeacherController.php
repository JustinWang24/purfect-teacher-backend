<?php


namespace App\Http\Controllers\Operator\OA;


use App\Dao\OA\OaAttendanceTeacherDao;
use App\Exports\AttendanceTotalExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;



class AttendanceTeacherController extends Controller
{
    public function management(MyStandardRequest $request){
        $dao = new OaAttendanceTeacherDao();
        $this->dataForView['pageTitle'] = '办公/考勤管理';
        $this->dataForView['groups'] = $dao->getAttendanceGroups($request->getSchoolId());
        return view('school_manager.oa.attendance',$this->dataForView);
    }
    public function members(Request $request,$id){
        $dao = new OaAttendanceTeacherDao();
        $this->dataForView['pageTitle'] = '办公/考勤管理/成员管理';
        $searchWord = strip_tags($request->get('search'));
        if(empty($searchWord)) {
            $this->dataForView['members'] = $dao->getNotAttendanceMembers($id);
        } else {
            $this->dataForView['members'] = $dao->searchNotAttendanceMembers($searchWord);
        }
        $this->dataForView['groupId'] = $id;
        return view('school_manager.oa.attendance_members',$this->dataForView);
    }
    public function messages(Request $request){
        $schoolId= $request->session()->get('school.id');
        $dao = new OaAttendanceTeacherDao();
        $this->dataForView['pageTitle'] = '办公/考勤管理/补卡管理';
        $this->dataForView['messages'] = $dao->getAttendanceMessagesByGroup($schoolId);

        return view('school_manager.oa.attendance_messages',$this->dataForView);
    }
    public function delmember(Request $request)
    {
        $schoolId= $request->session()->get('school.id');
        $userId = intval($request->get('id'));
        $groupId = intval($request->get('group'));
        $dao = new OaAttendanceTeacherDao();
        $result = $dao->deleteMember($userId,$groupId,$schoolId);


        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'考勤组成员删除成功');
            return redirect()->back();
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'考勤组成员删除失败');
            return redirect()->back();
        }
    }
    public function addMember(Request $request)
    {
        $schoolId= $request->session()->get('school.id');
        $userId = intval($request->get('id'));
        $groupId = intval($request->get('group'));
        $type = intval($request->get('type'));
        $dao = new OaAttendanceTeacherDao();
        if ($dao->getMember($userId)) {
            $result = $dao->updateMember($userId,$groupId,$schoolId,$type);
        } else {
            $result = $dao->addMember($userId,$groupId,$schoolId,$type);
        }

        if($result->getCode() == 1000){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'考勤组成员保存成功');
            return redirect()->route('school_manager.oa.attendances-members',['id'=>$groupId]);
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$result->getMessage());
            return redirect()->back();
        }


    }
    public function view(MyStandardRequest $request,$id){
        $dao = new OaAttendanceTeacherDao();
        $this->dataForView['pageTitle'] = '办公/考勤管理';
        $this->dataForView['group'] = $dao->getGroupInfoById($id);
        return view('school_manager.oa.attendance_form',$this->dataForView);
    }
    public function  save(Request $request)
    {
        $requestArr = $request->get('group');
        $requestArr['id'] = intval($requestArr['id']);
        $requestArr['name'] = strip_tags($requestArr['name']);
        $requestArr['morning_online_time'] = date('H:i:s',strtotime($requestArr['morning_online_time']));
        $requestArr['morning_offline_time'] = date('H:i:s',strtotime($requestArr['morning_offline_time']));
        $requestArr['afternoon_online_time'] = date('H:i:s',strtotime($requestArr['afternoon_online_time']));
        $requestArr['afternoon_offline_time'] = date('H:i:s',strtotime($requestArr['afternoon_offline_time']));
        $requestArr['night_online_time'] = date('H:i:s',strtotime($requestArr['night_online_time']));
        $requestArr['night_offline_time'] = date('H:i:s',strtotime($requestArr['night_offline_time']));
        $requestArr['wifi_name'] = strip_tags($requestArr['wifi_name']);
        $schoolId= $request->session()->get('school.id');
        $requestArr['school_id'] = $schoolId;
        $dao = new OaAttendanceTeacherDao();
        if(isset($requestArr['id'])){
            $result = $dao->updateGroup($requestArr);
        }
        else{
            $result = $dao->createGroup($requestArr);
        }
        if($result->getCode() == 1000){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$result->getData()->title.'考勤组保存成功');
            return redirect()->route('school_manager.oa.attendances-manager');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存考勤组');
            return redirect()->back();
        }

    }

    public function messageAccept(Request $request)
    {
        $id = intval($request->get('id'));
        $user = $request->user();
        $dao = new OaAttendanceTeacherDao();
        $message = $dao->getMessage($id);
        $result = $dao->messageAccept($message,$user);
        if($result->getCode() == 1000){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'补卡成功');
            return redirect()->back();
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$result->getMessage());
            return redirect()->back();
        }

    }

    public  function messageReject(Request $request)
    {
        $id = intval($request->get('id'));
        $user = $request->user();
        $dao = new OaAttendanceTeacherDao();
        $message = $dao->getMessage($id);
        $message->status = 2;
        $message->manager_user_id = $user->id;
        $message->save();
        FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'操作完成');
        return redirect()->back();
    }

    public function attendanceCourseList(Request $request)
    {
        $schoolId= $request->session()->get('school.id');
        $current = intval($request->get('current'));//前一周-1  后一周1
        $user = $request->user();
        $dao = new OaAttendanceTeacherDao();
        $userList = $dao->getUserList($schoolId, 'week', $current);
        $userTotal = [];
        foreach($userList as $user) {

            $user->total = $dao->countCourseNumByUser($schoolId, $user->user_id, 'week', $current);
        }
        $this->dataForView['users'] = $userList;
        $this->dataForView['current'] = $current;
        return view('school_manager.oa.attendance_total',$this->dataForView);

    }
    public function export(Request $request) {

        $schoolId= $request->session()->get('school.id');
        $current = intval($request->get('current'));//前一周-1  后一周1
        return Excel::download(new AttendanceTotalExport($schoolId,$current), 'attendance.xlsx');
    }
}
