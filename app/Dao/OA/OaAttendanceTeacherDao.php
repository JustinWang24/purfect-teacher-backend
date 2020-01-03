<?php


namespace App\Dao\OA;


use App\Dao\BuildFillableData;
use App\Dao\Schools\OrganizationDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Models\OA\AttendanceTeacher;
use App\Models\OA\AttendanceTeacherGroup;
use App\Models\OA\AttendanceTeachersGroupMember;
use App\Models\OA\AttendanceTeachersMacAddress;
use App\Models\OA\AttendanceTeachersMessage;
use App\Models\OA\OaAttendanceTeacher;
use App\Models\OA\OaAttendanceTeacherCourses;
use App\Models\OA\OaAttendanceTeacherGroup;
use App\Models\OA\OaAttendanceTeachersGroupMember;
use App\Models\Users\GradeUser;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OaAttendanceTeacherDao
{
    use BuildFillableData;
    public function __construct()
    {
    }

    public function createAttendanceTeacher($data)
    {
        return OaAttendanceTeacher::create($data);
    }

    /**
     * 获取用户当天的打卡记录
     * @param $userId
     * @param $schoolId
     * @return mixed
     */
    public function getTodayRecord($userId,$schoolId)
    {
        return OaAttendanceTeacher::where('user_id',$userId)
            ->where('school_id', $schoolId)
            ->where('check_in_date', date('Y-m-d'))
            ->first();
    }
    public function updateAttendanceTeacher($userId,$schoolId,$data)
    {
        if (!isset($data['check_in_date'])) {
            return OaAttendanceTeacher::where('user_id',$userId)
                ->where('check_in_date', date('Y-m-d'))
                ->where('school_id', $schoolId)
                ->update($data);
        } else {
            return AttendanceTeacher::where('user_id',$userId)
                ->where('school_id', $schoolId)
                ->update($data);
        }

    }
    public function getGroupInfo($userId,$schoolId)
    {
        $memberObj = OaAttendanceTeachersGroupMember::where('user_id', $userId)
            ->where('school_id',$schoolId)
            ->first();
        if ($memberObj) {
            return $memberObj->group;
        } else{
            return false;
        }

    }
    public function getGroupInfoById($groupId)
    {
        return OaAttendanceTeacherGroup::find($groupId);
    }


    public function getRecord($userId,$schoolId,$day)
    {
        return OaAttendanceTeacher::where('user_id',$userId)
            ->where('school_id', $schoolId)
            ->where('check_in_date', date('Y-m-d',strtotime($day)))
            ->first();
    }

    /**
     * 上班打卡结果 0-未打卡 1-正常打卡 2-迟到 3-严重迟到 5-缺卡
     * 下班打卡结果 0-未打卡 1-正常打卡 2-早退 5-缺卡
     * 打卡按钮 0-不在打卡时间 1-上班打卡 2-迟到 3-严重迟到 4-下班打卡 5-早退打卡 6-已打完卡
     */
    public function getStatus($record)
    {
        if (empty($record)) {
            return [
                'morning_online_status'=>'',
                'morning_offline_status'=>'',
                'afternoon_online_status'=>'',
                'afternoon_offline_status'=>'',
                'night_online_status'=>'',
                'night_offline_status'=>'',
            ];
        }
        $group = $record->member->group;
        $result = [];
        //上班状态
        if (empty($record->morning_online_mine)) {
            $result['morning_online_status'] = OaAttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->morning_online_mine) <= strtotime($record->check_in_date.' '.$group->morning_online_time)) {
            $result['morning_online_status'] = OaAttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->morning_online_mine) > strtotime($record->check_in_date.' '.$group->morning_online_time)) {
            $result['morning_online_status'] = OaAttendanceTeacherGroup::BELATE;
        }

        //下班状态
        if (empty($record->morning_offline_mine)) {
            $result['morning_offline_status'] = OaAttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->morning_offline_mine) >= strtotime($record->check_in_date.' '.$group->morning_offline_time)) {
            $result['morning_offline_status'] = OaAttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->morning_offline_mine) < strtotime($record->check_in_date.' '.$group->morning_offline_time)) {
            $result['morning_offline_status'] = OaAttendanceTeacherGroup::LEAVEEARLY;
        }
        //上班状态
        if (empty($record->afternoon_online_mine)) {
            $result['afternoon_online_status'] = OaAttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->afternoon_online_mine) <= strtotime($record->check_in_date.' '.$group->afternoon_online_time)) {
            $result['afternoon_online_status'] = OaAttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->afternoon_online_mine) > strtotime($record->check_in_date.' '.$group->afternoon_online_time)) {
            $result['afternoon_online_status'] = OaAttendanceTeacherGroup::BELATE;
        }

        //下班状态
        if (empty($record->afternoon_offline_mine)) {
            $result['afternoon_offline_status'] = OaAttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->afternoon_offline_mine) >= strtotime($record->check_in_date.' '.$group->afternoon_offline_time)) {
            $result['afternoon_offline_status'] = OaAttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->afternoon_offline_mine) < strtotime($record->check_in_date.' '.$group->afternoon_offline_time)) {
            $result['afternoon_offline_status'] = OaAttendanceTeacherGroup::LEAVEEARLY;
        }
        //上班状态
        if (empty($record->night_online_mine)) {
            $result['night_online_status'] = OaAttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->night_online_mine) <= strtotime($record->check_in_date.' '.$group->night_online_time)) {
            $result['night_online_status'] = OaAttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->night_online_mine) > strtotime($record->check_in_date.' '.$group->night_online_time)) {
            $result['night_online_status'] = OaAttendanceTeacherGroup::BELATE;
        }

        //下班状态
        if (empty($record->night_offline_mine)) {
            $result['night_offline_status'] = OaAttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->night_offline_mine) >= strtotime($record->check_in_date.' '.$group->night_offline_time)) {
            $result['night_offline_status'] = OaAttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->night_offline_mine) < strtotime($record->check_in_date.' '.$group->night_offline_time)) {
            $result['night_offline_status'] = OaAttendanceTeacherGroup::LEAVEEARLY;
        }
        $result['button_status'] = $this->getBtnStatus($record->user_id, $record->school_id);
        return $result;
    }
    //获取某个月的打卡记录
    public function getMonthList($userId, $month='')
    {
        $timeArr = $this->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $row = OaAttendanceTeacher::where('user_id',$userId)
            ->where('check_in_date','>=',$start)
            ->where('check_in_date','<',$end)
            ->get();
        $output = [];
        foreach($row as $key=>$value) {
            $output[$value->check_in_date] = $value;
        }
        return $output;
    }
    public function getStartAndEndArr($month)
    {
        if(empty($month)) {
            $start = date('Y-m-d', strtotime('first day of this month'));
            $end  = date('Y-m-d', strtotime('first day of next month'));
        } else {
            $start = date('Y-m-d', strtotime(strval($month)."-01"));
            $end  = date('Y-m-d', strtotime('+1 month', strtotime($start)));
        }
        return ['start'=>$start,'end'=>$end];
    }
    //查询补卡记录
    public function getMessageRecord($userId,$date)
    {
        return OaAttendanceTeachersMessage::where('user_id',$userId)
            ->where('attendance_date',$date)
            ->first();
    }
    //根据补卡记录的操作情况判断补卡状态
    public function getMessageOptStatus($userId,$date)
    {
        $message = $this->getMessageRecord($userId, $date);
        if (empty($message)) {
            //没有申请记录没有考勤记录说明未补卡
            $status = OaAttendanceTeachersMessage::NOTOPT;
        } else {
            //有补卡记录但是考勤表没有记录说明被拒绝
            if ($message->status == 2) {
                $status = OaAttendanceTeachersMessage::CANCLE;
            } else {
                //有补卡记录但是补卡记录没有完成说明正在补卡中
                $status = OaAttendanceTeachersMessage::DOINGOPT;
            }
        }
        return $status;
    }
    //修改macAddress
    public function updateMacAddress($userId,$macAddress)
    {
        return OaAttendanceTeachersGroupMember::where('user_id', $userId)->update(['mac_address'=>$macAddress]);
    }
    //查询macAddress
    public function getMacAddress($userId, $schoolId)
    {
        $member = OaAttendanceTeachersGroupMember::where('user_id', $userId)->where('school_id', $schoolId)->first();
        if ($member) {
            return $member->mac_address;
        } else {
            return '';
        }
    }

    /**
     * 获得当前时间的按钮状态
     * @param $userId
     * @param $schoolId
     * @return int
     */
    public function getBtnStatus($userId, $schoolId)
    {
        $group = $this->getGroupInfo($userId, $schoolId);
        //按钮状态
        $time = time();
        if (date('N',$time)>5) {
            return OaAttendanceTeacherGroup::BTNNULL;
        } elseif($time < strtotime($group->morning_online_time)) {
            return OaAttendanceTeacherGroup::CHECKED;
        } elseif($time > strtotime($group->night_offline_time)) {
            return OaAttendanceTeacherGroup::OFFWORK;
        } elseif($time > strtotime($group->morning_online_time) && $time < strtotime($group->morning_offline_time) ) {
            $point = strtotime($group->morning_online_time) + (strtotime($group->morning_offline_time)-strtotime($group->morning_online_time))/2;
            if ($time > $point) {
                return OaAttendanceTeacherGroup::LEAVEEARLY;
            } else {
                return OaAttendanceTeacherGroup::BELATE;
            }
        } elseif($time > strtotime($group->morning_offline_time) && $time <= strtotime($group->afternoon_online_time)) {
            $point = strtotime($group->morning_offline_time) + (strtotime($group->afternoon_online_time)-strtotime($group->morning_offline_time))/2;
            if ($time > $point) {
                return OaAttendanceTeacherGroup::CHECKED;
            } else {
                return OaAttendanceTeacherGroup::OFFWORK;
            }
        } elseif($time > strtotime($group->afternoon_online_time) && $time < strtotime($group->afternoon_offline_time) ) {
            $point = strtotime($group->afternoon_online_time) + (strtotime($group->afternoon_offline_time)-strtotime($group->afternoon_online_time))/2;
            if ($time > $point) {
                return OaAttendanceTeacherGroup::LEAVEEARLY;
            } else {
                return OaAttendanceTeacherGroup::BELATE;
            }
        } elseif($time > strtotime($group->afternoon_offline_time) && $time <= strtotime($group->night_online_time)) {
            $point = strtotime($group->afternoon_offline_time) + (strtotime($group->night_online_time)-strtotime($group->afternoon_offline_time))/2;
            if ($time > $point) {
                return OaAttendanceTeacherGroup::CHECKED;
            } else {
                return OaAttendanceTeacherGroup::OFFWORK;
            }
        } elseif($time > strtotime($group->night_online_time) && $time < strtotime($group->night_offline_time) ) {
            $point = strtotime($group->night_online_time) + (strtotime($group->night_offline_time)-strtotime($group->night_online_time))/2;
            if ($time > $point) {
                return OaAttendanceTeacherGroup::LEAVEEARLY;
            } else {
                return OaAttendanceTeacherGroup::BELATE;
            }
        }
    }
    public function createEditMacAddressApply($userId, $schoolId, $macAddress, $content)
    {
        //先查询，如果存在就更新，否则创建
        $row = OaAttendanceTeachersMacAddress::where('user_id',$userId)->where('status',1)->first();
        if ($row) {
            return OaAttendanceTeachersMacAddress::where('user_id',$userId)->where('status',1)->update([
                'user_id' => $userId,
                'mac_address'=>$macAddress,
                'school_id' => $schoolId,
                'content' =>$content
            ]);
        } else {
            return OaAttendanceTeachersMacAddress::create([
                'user_id' => $userId,
                'mac_address'=>$macAddress,
                'school_id' => $schoolId,
                'content' =>$content
            ]);
        }

    }
    public function getAttendanceGroups($schoolId)
    {
        return OaAttendanceTeacherGroup::where('school_id',$schoolId)->
            orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    public function createMessage($data)
    {
        return OaAttendanceTeachersMessage::create($data);
    }

    public function updateGroup($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new OaAttendanceTeacherGroup(), $data);
            $group = OaAttendanceTeacherGroup::where('id', $id)->update($fillableData);
            if ($group) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData(OaAttendanceTeacherGroup::find($id));
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新考勤组失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }
    public function getAttendanceMembers($groupId)
    {
        return OaAttendanceTeachersGroupMember::where('group_id',$groupId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function getNotAttendanceMembers($groupId)
    {
        $hasMembers = OaAttendanceTeachersGroupMember::where('group_id',$groupId)->pluck('user_id')->all();
        return GradeUser::whereNotIn('user_id', $hasMembers)->whereIn('user_type',[9,10])
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function searchNotAttendanceMembers($name)
    {
        return GradeUser::select(['id','user_id','name','user_type','department_id','major_id','grade_id'])
            ->whereIn('user_type',[9,10])
            ->where('name','like', $name.'%')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    public function addMember($userId,$groupId,$schoolId, $type=1){
        $status = $type==2?2:1;
        $data = [
            'user_id'=>$userId,
            'group_id' => $groupId,
            'status'   => $status,
            'school_id' => $schoolId,
        ];
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new OaAttendanceTeachersGroupMember(), $data);
            $member = OaAttendanceTeachersGroupMember::create($fillableData);
            if ($member) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } else {
                DB::rollBack();
                $messageBag->setMessage('添加成员失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }
    public function updateMember($userId,$groupId,$schoolId, $type=1){
        $status = $type==2?2:1;
        $data = [
            'user_id'=>$userId,
            'group_id' => $groupId,
            'status'   => $status,
            'school_id' => $schoolId,
        ];
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new OaAttendanceTeachersGroupMember(), $data);
            $member = OaAttendanceTeachersGroupMember::where('user_id',$userId)
                ->where('school_id',$schoolId)
                ->update($fillableData);
            if ($member) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新成员失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }
    public function getMember($userId)
    {
        return OaAttendanceTeachersGroupMember::where('user_id',$userId)->first();
    }
    public function deleteMember($userId,$groupId,$schoolId) {
        return OaAttendanceTeachersGroupMember::where('user_id',$userId)
            ->where('group_id',$groupId)
            ->where('school_id',$schoolId)
            ->delete();
    }
    public function getAttendanceMessages($userId)
    {
        return OaAttendanceTeachersMessage::where('user_id',$userId)
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function getAttendanceMessagesByGroup($schoolId)
    {
        return OaAttendanceTeachersMessage::where('school_id',$schoolId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function  getMessage($id)
    {
        return OaAttendanceTeachersMessage::find($id);
    }

    public function messageAccept($message,$user)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $targetUserId = $message->user_id;
            $group = $this->getGroupInfo($targetUserId,$message->school_id);
            $record = $this->getRecord($message->user_id,$message->school_id,$message->attendance_date);
            $data = [];
            if ($message->type == 1) {
                $data['online_mine']  = $message->attendance_date. ' ' .$message->attendance_time;
            }elseif($message->type == 2){
                $data['offline_mine'] = $message->attendance_date. ' ' .$message->attendance_time;
            } elseif($message->type == 3){
                $data['online_mine']  = $message->attendance_date. ' ' .$group->online_time;
                $data['offline_mine'] = $message->attendance_date. ' ' .$group->offline_time;
            }
            $data['wifi']           = $group->wifi_name;
            $data['mac_address']    = $message->member->mac_address;
            $data['check_in_date']  = $message->attendance_date;
            $data['status']         = 2;
            $data['school_id']      = $message->school_id;
            $data['user_id']        = $targetUserId;
            if ($record) {
                $result = $this->updateAttendanceTeacher($targetUserId,$message->school_id,$data);
            } else {
                $result = $this->createAttendanceTeacher($data);
            }
            if ($result) {
                $message->status = 2;
                $message->manager_user_id = $user->id;
                $message->save();
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新成员失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function getTodayCourseRecord($userId, $schoolId, $timeTableId)
    {
        return OaAttendanceTeacherCourses::where('user_id', $userId)
            ->where('school_id', $schoolId)
            ->where('timetable_items_id', $timeTableId)
            ->where('check_in_date', date('Y-m-d'))
            ->first();
    }

    /**
     * 更新上课打卡记录
     * @param $userId
     * @param $schoolId
     * @param $data
     * @return mixed
     */
    public function updateAttendanceTeacherCourse($userId,$schoolId,$data)
    {
        if (!isset($data['check_in_date'])) {
            return OaAttendanceTeacherCourses::where('user_id',$userId)
                ->where('check_in_date', date('Y-m-d'))
                ->where('timetable_items_id', $data['timetable_items_id'])
                ->where('school_id', $schoolId)
                ->update($data);
        } else {
            return OaAttendanceTeacherCourses::where('user_id',$userId)
                ->where('timetable_items_id', $data['timetable_items_id'])
                ->where('school_id', $schoolId)
                ->update($data);
        }

    }
    public function createAttendanceTeacherCourse($data)
    {
        return OaAttendanceTeacherCourses::create($data);
    }

    public function  getAttendanceTeacherCourseListByTimeTabIds($schoolId, $timeTableIds)
    {
        return OaAttendanceTeacherCourses::where('check_in_date', date('Y-m-d'))
            ->whereIn('timetable_items_id', $timeTableIds)
            ->where('school_id', $schoolId)
            ->get();
    }

    //发短信
    //获取所有课程
    //获取所有已经打卡记录
    //获取所有需要收到短信的用户
    public function getPushlist($schoolId)
    {
        $timeTableDao = new TimetableItemDao();
        $timeTableList = $timeTableDao->getCourseListByCurrentTime($schoolId);
        $timeTableIds = [];
        $AttendanceTimeTableIds = [];
        $noTimeTableIds = [];
        if(count($timeTableList) == 0) {
            return [];
        }
        foreach ($timeTableList as $timeTableItem)
        {
            $timeTableIds[] = $timeTableItem->id;
        }
        $attendanceList = $this->getAttendanceTeacherCourseListByTimeTabIds($schoolId,$timeTableIds);
        if (!empty($attendanceList)) {
            foreach ($attendanceList as $attendanceItem)
            {
                $AttendanceTimeTableIds[] = $attendanceItem->timetable_items_id;
            }
        }

        $noTimeTableIds = array_diff($timeTableIds,$AttendanceTimeTableIds);
        //判断时间是否已经超过三分钟
        $ids = [];
        foreach($noTimeTableIds as $timeTableId)
        {
            $timeTableObj = $timeTableDao->getItemById($timeTableId, true);
            $timeSlot = strtotime($timeTableObj->timeSlot->from);
            $diff = ceil((time() - $timeSlot)/60);
            if ($diff >3) {
                $ids[] = $timeTableId;
            }
        }
        return $ids;
    }

    public function getUserListForReceiveMessage($schoolId){
        $organizationDao = new OrganizationDao();
        //TODO 给教务处老师发短信，此处临时实现，应该有个配置来设置哪些老师或者哪个组织来接收
        $organization = $organizationDao->getByName($schoolId, '教务处');
        $orgMembers = $organizationDao->getMembers($schoolId, $organization->id);
        return $orgMembers;
    }
}
