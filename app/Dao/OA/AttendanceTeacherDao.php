<?php


namespace App\Dao\OA;


use App\Dao\BuildFillableData;
use App\Models\OA\AttendanceTeacher;
use App\Models\OA\AttendanceTeacherGroup;
use App\Models\OA\AttendanceTeachersGroupMember;
use App\Models\OA\AttendanceTeachersMacAddress;
use App\Models\OA\AttendanceTeachersMessage;
use App\Models\Users\GradeUser;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceTeacherDao
{
    use BuildFillableData;
    public function __construct()
    {
    }

    public function createAttendanceTeacher($data)
    {
        return AttendanceTeacher::create($data);
    }

    public function getTodayRecord($userId,$schoolId)
    {
        return AttendanceTeacher::where('user_id',$userId)
            ->where('school_id', $schoolId)
            ->where('check_in_date', date('Y-m-d'))
            ->first();
    }
    public function updateAttendanceTeacher($userId,$schoolId,$data)
    {
        if (!isset($data['check_in_date'])) {
            return AttendanceTeacher::where('user_id',$userId)
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
        $memberObj = AttendanceTeachersGroupMember::where('user_id', $userId)
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
        return AttendanceTeacherGroup::find($groupId);
    }


    public function getRecord($userId,$schoolId,$day)
    {
        return AttendanceTeacher::where('user_id',$userId)
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
            return ['online_status'=>'','offline_status'=>''];
        }
        $group = $record->member->group;
        $result = [];
        //上班状态
        if (empty($record->online_mine)) {
            $result['online_status'] = AttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->online_mine) <= strtotime($record->check_in_date.' '.$group->online_time)) {
            $result['online_status'] = AttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->online_mine) > strtotime($record->check_in_date.' '.$group->online_time)) {
            $diff = strtotime($record->online_mine)-strtotime($record->check_in_date.' '.$group->online_time);
            if ($diff > $group->late_duration*60) {
                $result['online_status'] = AttendanceTeacherGroup::SERIOUSLATE;
            } elseif($diff <= $group->late_duration*60) {
                $result['online_status'] = AttendanceTeacherGroup::BELATE;
            }
        }

        //下班状态
        if (empty($record->offline_mine)) {
            $result['offline_status'] = AttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->offline_mine) >= strtotime($record->check_in_date.' '.$group->offline_time)) {
            $result['offline_status'] = AttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->offline_mine) < strtotime($record->check_in_date.' '.$group->offline_time)) {
            $result['offline_status'] = AttendanceTeacherGroup::LEAVEEARLY;
        }

        //按钮状态
        $time = time();
        if (date('N',$time)>5) {
            $result['button_status'] = AttendanceTeacherGroup::BTNNULL;
        } elseif($time < strtotime($group->online_time) && empty($record->online_mine)) {
            $result['button_status'] = AttendanceTeacherGroup::CHECKED;
        } elseif($time > strtotime($group->offline_time) && empty($record->offline_mine)) {
            $result['button_status'] = AttendanceTeacherGroup::OFFWORK;
        } elseif(empty($record->online_mine) && $time > strtotime($group->online_time)) {
            $diff = $time-strtotime($group->online_time);
            if ($diff > $group->serious_late_duration*60) {
                $result['button_status'] = AttendanceTeacherGroup::SERIOUSLATE;
            } elseif($diff <= $group->late_duration*60) {
                $result['button_status'] = AttendanceTeacherGroup::BELATE;
            }
        } elseif(empty($record->offline_mine) && $time > strtotime($group->online_time)) {
            if ($time < strtotime($group->offline_time)) {
                $result['button_status'] = AttendanceTeacherGroup::BTNLEAVEEARLY;
            } elseif($time > strtotime($group->offline_time)) {
                $result['button_status'] = AttendanceTeacherGroup::OFFWORK;
            }
        } elseif(!empty($record->online_mine) && !empty($record->offline_mine)) {
            $result['button_status'] = AttendanceTeacherGroup::BTNFINISH;
        }
            return $result;
    }
    //获取某个月的打卡记录
    public function getMonthList($userId, $month='')
    {
        $timeArr = $this->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $row = AttendanceTeacher::where('user_id',$userId)
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
        return AttendanceTeachersMessage::where('user_id',$userId)
            ->where('attendance_date',$date)
            ->first();
    }
    //根据补卡记录的操作情况判断补卡状态
    public function getMessageOptStatus($userId,$date)
    {
        $message = $this->getMessageRecord($userId, $date);
        if (empty($message)) {
            //没有申请记录没有考勤记录说明未补卡
            $status = AttendanceTeachersMessage::NOTOPT;
        } else {
            //有补卡记录但是考勤表没有记录说明被拒绝
            if ($message->status == 2) {
                $status = AttendanceTeachersMessage::CANCLE;
            } else {
                //有补卡记录但是补卡记录没有完成说明正在补卡中
                $status = AttendanceTeachersMessage::DOINGOPT;
            }
        }
        return $status;
    }
    //修改macAddress
    public function updateMacAddress($userId,$macAddress)
    {
        return AttendanceTeachersGroupMember::where('user_id', $userId)->update(['mac_address'=>$macAddress]);
    }
    //查询macAddress
    public function getMacAddress($userId, $schoolId)
    {
        return AttendanceTeachersGroupMember::where('user_id', $userId)->where('school_id', $schoolId)->first();
    }

    public function getBtnStatus($userId, $schoolId)
    {
        $group = $this->getGroupInfo($userId, $schoolId);
        //按钮状态
        $time = time();
        if (date('N',$time)>5) {
            return AttendanceTeacherGroup::BTNNULL;
        } elseif($time < strtotime($group->online_time)) {
            return AttendanceTeacherGroup::CHECKED;
        } elseif($time > strtotime($group->offline_time)) {
            return AttendanceTeacherGroup::OFFWORK;
        } elseif($time > strtotime($group->online_time)) {
            $diff = $time-strtotime($group->online_time);
            if ($diff > $group->serious_late_duration*60) {
                return AttendanceTeacherGroup::SERIOUSLATE;
            } elseif($diff <= $group->late_duration*60) {
                return AttendanceTeacherGroup::BELATE;
            }
        } elseif($time > strtotime($group->online_time)) {
            if ($time < strtotime($group->offline_time)) {
                return AttendanceTeacherGroup::BTNLEAVEEARLY;
            } elseif($time > strtotime($group->offline_time)) {
                return AttendanceTeacherGroup::OFFWORK;
            }
        }
    }
    public function createEditMacAddressApply($userId, $schoolId, $macAddress, $content)
    {
        //先查询，如果存在就更新，否则创建
        $row = AttendanceTeachersMacAddress::where('user_id',$userId)->where('status',1)->first();
        if ($row) {
            return AttendanceTeachersMacAddress::where('user_id',$userId)->where('status',1)->update([
                'user_id' => $userId,
                'mac_address'=>$macAddress,
                'school_id' => $schoolId,
                'content' =>$content
            ]);
        } else {
            return AttendanceTeachersMacAddress::create([
                'user_id' => $userId,
                'mac_address'=>$macAddress,
                'school_id' => $schoolId,
                'content' =>$content
            ]);
        }

    }
    public function getAttendanceGroups($schoolId)
    {
        return AttendanceTeacherGroup::where('school_id',$schoolId)->
            orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    public function createMessage($data)
    {
        return AttendanceTeachersMessage::create($data);
    }

    public function updateGroup($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new AttendanceTeacherGroup(), $data);
            $group = AttendanceTeacherGroup::where('id', $id)->update($fillableData);
            if ($group) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData(AttendanceTeacherGroup::find($id));
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
        return AttendanceTeachersGroupMember::where('group_id',$groupId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function getNotAttendanceMembers($groupId)
    {
        $hasMembers = AttendanceTeachersGroupMember::where('group_id',$groupId)->pluck('user_id')->all();
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
            $fillableData = $this->getFillableData(new AttendanceTeachersGroupMember(), $data);
            $member = AttendanceTeachersGroupMember::create($fillableData);
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
            $fillableData = $this->getFillableData(new AttendanceTeachersGroupMember(), $data);
            $member = AttendanceTeachersGroupMember::where('user_id',$userId)
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
        return AttendanceTeachersGroupMember::where('user_id',$userId)->first();
    }
    public function deleteMember($userId,$groupId,$schoolId) {
        return AttendanceTeachersGroupMember::where('user_id',$userId)
            ->where('group_id',$groupId)
            ->where('school_id',$schoolId)
            ->delete();
    }
    public function getAttendanceMessages($userId)
    {
        return AttendanceTeachersMessage::where('user_id',$userId)
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function getAttendanceMessagesByGroup($schoolId)
    {
        return AttendanceTeachersMessage::where('school_id',$schoolId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    public function  getMessage($id)
    {
        return AttendanceTeachersMessage::find($id);
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
}
