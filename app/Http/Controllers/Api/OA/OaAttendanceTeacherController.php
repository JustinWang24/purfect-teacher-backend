<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\OA\OaAttendanceTeacherDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Models\OA\OaAttendanceLeaveAndVisits;
use App\Models\OA\OaAttendanceTeacher;
use App\Models\OA\OaAttendanceTeacherGroup;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OaAttendanceTeacherController extends Controller
{
    /**
     * 打卡
     * @param Request $request
     * @return string
     */
    public function postTodayInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $wifi = strip_tags($request->get('wifi'));
        $mac_address = strip_tags($request->get('mac_address'));
        $dao = new OaAttendanceTeacherDao();
        $row = $dao->getTodayRecord($user->id,$schoolId);
        $time = time();
        $sqlTime = date('Y-m-d H:i:s',$time);
        $data = [];
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $afternoonPoint = strtotime($group->morning_offline_time) + (strtotime($group->afternoon_online_time)-strtotime($group->morning_offline_time))/2;
        $nightPoint = strtotime($group->afternoon_offline_time) + (strtotime($group->night_online_time)-strtotime($group->afternoon_offline_time))/2;
        if ($row) {
            /**
             *  在等号区间内的打卡行为以下班时间和上班时间中间时间点来计算，超过中间时间点的算上班，中间时间点以前的算下班。

            ​      9点         12点                   15点              18点                19点              21点
            =======|            |====afternoonPoint====|                 |====nightPoint====|                 |========

             */
            if ($time <= $afternoonPoint){
                if (empty($row->morning_online_time)) {
                    $data['morning_online_time'] = $sqlTime;
                } else {
                    $data['morning_offline_time'] = $sqlTime;
                }

            } elseif($time <= $nightPoint) {
                if (empty($row->afternoon_online_time)) {
                    $data['afternoon_online_time'] = $sqlTime;
                } else {
                    $data['afternoon_offline_time'] = $sqlTime;
                }
            } else {
                if (empty($row->night_online_time)) {
                    $data['night_online_time'] = $sqlTime;
                } else {
                    $data['night_offline_time'] = $sqlTime;
                }
            }
            $data['wifi']           = $wifi;
            $data['mac_address']    = $mac_address;
            $result = $dao->updateAttendanceTeacher($user->id,$schoolId,$data);
        } else {
            $data = [
                'user_id'   => $user->id,
                'wifi'      => $wifi,
                'mac_address'=> $mac_address,
                'check_in_date'=>date('Y-m-d'),
                'school_id' => $schoolId,
            ];
            if ($time <= $afternoonPoint){
                $data['morning_online_time'] = $sqlTime;
            } elseif($time <= $nightPoint) {
                $data['afternoon_online_time'] = $sqlTime;
            } else {
                $data['night_online_time'] = $sqlTime;
            }
            $result = $dao->createAttendanceTeacher($data);
        }
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }
    public function getTodayInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $day = date('Y-m-d', strtotime($request->get('day',date('Y-m-d'))));
        $dao = new OaAttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        if (empty($group)) {
            return JsonBuilder::Error('您还没有加入一个考勤组，请联系管理员');
        }
        $record = $dao->getRecord($user->id,$schoolId,$day);
        if(empty($record)) {
            return JsonBuilder::Success('没有记录');
        }
        $status = $dao->getStatus($record);
        $output = [
            'wifi_name' => $record->wifi,
            'user_name' => $record->user->name,
            'head_img'  => $record->user->profile->avatar,
            'group_name'=> $group->name,
            'date'      => $day,
            'morning_online_time'       => $group->morning_online_time,
            'morning_online_mine'       => $record->morning_online_mine,
            'morning_online_status'     => $status['morning_online_status'],
            'morning_offline_time'      => $group->morning_offline_time,
            'morning_offline_mine'      => $record->morning_offline_mine,
            'morning_offline_status'    => $status['morning_offline_status'],
            'afternoon_online_time'     => $group->afternoon_online_time,
            'afternoon_online_mine'     => $record->afternoon_online_mine,
            'afternoon_online_status'   => $status['afternoon_online_status'],
            'afternoon_offline_time'    => $group->afternoon_offline_time,
            'afternoon_offline_mine'    => $record->afternoon_offline_mine,
            'afternoon_offline_status'  => $status['afternoon_offline_status'],
            'night_online_time'         => $group->night_online_time,
            'night_online_mine'         => $record->night_online_mine,
            'night_online_status'       => $status['night_online_status'],
            'night_offline_time'        => $group->night_offline_time,
            'night_offline_mine'        => $record->night_offline_mine,
            'night_offline_status'      => $status['night_offline_status'],
            'button_status'             => $status['button_status'],
            'mac_address'               => $record->mac_address,
        ];
        return JsonBuilder::Success($output);

    }


    public function mac_add(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $oldMacAddress = $dao->getMacAddress($user->id, $schoolId);
        if ($oldMacAddress) {
            return JsonBuilder::Success('操作成功');
        }
        $mac_address = strip_tags($request->get('mac_address'));
        if (empty($mac_address)) {
            return JsonBuilder::Error('数据错误，没有获取到设备标识');
        }
        $result = $dao->updateMacAddress($user->id,$mac_address);
        if($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }


    }

    /**
     * 上课打卡
     * @param Request $request
     * @return string
     */
    public function postTodayCourseInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $wifi = strip_tags($request->get('wifi'));
        $mac_address = strip_tags($request->get('mac_address'));
        $qrData = strip_tags($request->get('qr_data'));
        $qrStrArr = explode(',',base64_decode($qrData));
        $grade_id = $qrStrArr[2];
        $timeTableId = $qrStrArr[3];

        $dao = new OaAttendanceTeacherDao();
        $timeTableDao = new TimetableItemDao();
        $timeTableObj = $timeTableDao->getItemById($timeTableId, true);
        $row = $dao->getTodayCourseRecord($user->id,$schoolId,$timeTableId);
        $time = time();
        $sqlTime = date('Y-m-d H:i:s',$time);
        $data = [];
        if ($row) {
            if (empty($row->online_time)) {
                $data['online_mine'] = $sqlTime;
            } else {
                $data['offline_mine'] = $sqlTime;
            }
            $data['wifi']           = $wifi;
            $data['mac_address']    = $mac_address;
            $data['timetable_items_id']= $timeTableId;
            $result = $dao->updateAttendanceTeacherCourse($user->id,$schoolId,$data);
        } else {
            $data = [
                'user_id'   => $user->id,
                'wifi'      => $wifi,
                'mac_address'=> $mac_address,
                'check_in_date'=>date('Y-m-d'),
                'school_id' => $schoolId,
                'grade_id' => $grade_id,
                'plan_user_id' => $timeTableObj->teacher_id,
                'timetable_items_id' => $timeTableId,
                'course_id' => $timeTableObj->course_id,
            ];
            $data['online_mine'] = $sqlTime;
            $result = $dao->createAttendanceTeacherCourse($data);
        }
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    /**
     * 根据云班牌信息返回当前时间当前教室的课程情况，教室看到此情况后判断自己是否打卡
     * @param Request $request
     * @return string
     */
    public function getCoursesIndoByCloudGradeId(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $qrData = strip_tags($request->get('qr_data'));
        $qrStrArr = explode(',',base64_decode($qrData));
        $timeTableId = $qrStrArr[3];
        $timeTableDao = new TimetableItemDao();
        $timeTableObj = $timeTableDao->getItemById($timeTableId, true);
        return JsonBuilder::Success($timeTableObj);
    }

    public function getJsMonthCount(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $month = strip_tags($request->get('month'));
        $dao = new OaAttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $result = $dao->getMonthList($user->id,$month);
        $timeArr = $dao->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $missDay = 0;
        $days_count = 0;
        $normal_count = 0;
        $rest_count = 0;
        $not_count = 0;
        $late_count = 0;
        $early_count = 0;
        $miss_count = 0;
        $approver_count = 0;
        $normal_list = [];
        $rest_list = [];
        $not_list = [];
        $late_list = [];
        $early_list = [];
        $approver_list = [];
        $miss_list = [];
        while ($start<$end)
        {
            //统计当月天数
            $days_count++;
            //统计休息日天数
            if (date('N',strtotime($start))>5) {
                $rest_count++;
                $rest_list[] = [
                    'day'=>$start,
                ];
            }

            if (!isset($result[$start]) && date('N',strtotime($start))<=5) {
                //旷工日统计
                $not_count++;
                $not_list[] = [
                    'day'=>$start,
                ];
            } elseif(isset($result[$start])) {
                $status = $dao->getStatus($result[$start]);
                //出勤日统计
                $normal_count++;
                $normal_list[] = [
                    'day'=>$start,
                    'morning_start_status'=>$status['morning_online_status'],
                    'morning_end_status'=>$status['morning_offline_status'],
                    'afternoon_start_status'=>$status['afternoon_online_status'],
                    'afternoon_end_status'=>$status['afternoon_offline_status'],
                    'night_start_status'=>$status['night_online_status'],
                    'night_end_status'=>$status['night_offline_status'],
                ];
                //迟到
                if(strtotime($result[$start]->morning_online_mine) > strtotime($result[$start]->check_in_date.' '.$group->morning_online_time))
                {
                    $late_count++;
                    $late_list[] = [
                        'day'=>$start,
                        'time'=>$group->morning_online_time,
                        'status'=>$status['morning_online_status'],
                    ];
                }
                if(strtotime($result[$start]->afternoon_online_mine) > strtotime($result[$start]->check_in_date.' '.$group->afternoon_online_time))
                {
                    $late_count++;
                    $late_list[] = [
                        'day'=>$start,
                        'time'=>$group->afternoon_online_time,
                        'status'=>$status['afternoon_online_status'],
                    ];
                }
                if(strtotime($result[$start]->night_online_mine) > strtotime($result[$start]->check_in_date.' '.$group->night_online_time))
                {
                    $late_count++;
                    $late_list[] = [
                        'day'=>$start,
                        'time'=>$group->night_online_time,
                        'status'=>$status['night_online_status'],
                    ];
                }
                //补卡
                if($result[$start]->status == 2) {
                    $approver_count++;
                    $approver_list[] = [
                        'day'=>$start,
                    ];
                }

                //早退
                if (strtotime($result[$start]->morning_offline_mine) < strtotime($result[$start]->check_in_date.' '.$group->morning_offline_time))
                {
                    $early_count++;
                    $early_list[] = [
                        'day'=>$start,
                        'time'=>$group->morning_offline_mine,
                        'end_status'=>$status['morning_offline_status'],
                    ];
                }
                if (strtotime($result[$start]->afternoon_offline_mine) < strtotime($result[$start]->check_in_date.' '.$group->afternoon_offline_time))
                {
                    $early_count++;
                    $early_list[] = [
                        'day'=>$start,
                        'time'=>$group->afternoon_offline_mine,
                        'end_status'=>$status['afternoon_offline_status'],
                    ];
                }
                if (strtotime($result[$start]->night_offline_mine) < strtotime($result[$start]->check_in_date.' '.$group->night_offline_time))
                {
                    $early_count++;
                    $early_list[] = [
                        'day'=>$start,
                        'time'=>$group->night_offline_mine,
                        'end_status'=>$status['night_offline_status'],
                    ];
                }
                //缺卡记录
                $status = $dao->getMessageOptStatus($user->id,$start);
                if (empty($result[$start]->morning_online_mine)) {
                    $miss_count++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->morning_online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->morning_offline_mine)) {
                    $miss_count++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->morning_offline_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                }
                if (empty($result[$start]->afternoon_online_mine)) {
                    $miss_count++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->afternoon_online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->afternoon_offline_mine)) {
                    $miss_count++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->afternoon_offline_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                }
                if (empty($result[$start]->night_online_mine)) {
                    $miss_count++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->night_online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->night_offline_mine)) {
                    $miss_count++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->night_offline_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                }

            }
            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }
        $data = [
            'user_name' => $user->name,
            'head_img'  => $user->profile->avatar,
            'group_name'=> $group->name,
            'month_select'=>$month,
            'days_count' => $days_count,
            'normal_count'=>$normal_count,
            'normal_list' => $normal_list,
            'rest_count' => $rest_count,
            'rest_list'  => $rest_list,
            'not_count'  => $not_count,
            'early_count'=> $early_count,
            'early_list' => $early_list,
            'miss_count' => $missDay,
            'miss_list' => $miss_list,
            'approver_count' => $approver_count,
            'approver_list'  => $approver_list
        ];
        return JsonBuilder::Success($data);
    }

    public function getMissList(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $month = strip_tags($request->get('month'));
        $dao = new OaAttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $result = $dao->getMonthList($user->id,$month);
        $timeArr = $dao->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $missDay = 0;
        $miss_list = [];
        while ($start<$end)
        {
            if (!isset($result[$start]) && date('N',strptime($start))<=5) {

            } else {
                $status = $dao->getMessageOptStatus($user->id,$start);
                if (empty($result[$start]->morning_online_mine)) {
                    $missDay++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->morning_online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->morning_offline_mine)) {
                    $missDay++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->morning_offline_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                }
                if (empty($result[$start]->afternoon_online_mine)) {
                    $missDay++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->afternoon_online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->afternoon_offline_mine)) {
                    $missDay++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->afternoon_offline_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                }
                if (empty($result[$start]->night_online_mine)) {
                    $missDay++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->night_online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->night_offline_mine)) {
                    $missDay++;
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->night_offline_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                }

            }
            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }
        $data = [
            'user_name' => $user->name,
            'head_img'  => $user->profile->avatar,
            'group_name'=> $group->name,
            'month_select'=>$month,
            'miss_count' => $missDay,
            'miss_list' => $miss_list
        ];
        return JsonBuilder::Success($data);
    }

    public function get_relationship(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $list = $dao->getManagerByMembers($schoolId, $group->id);
        $output = [];
        foreach ($list as $k => $item) {
            $output[$k]['duties'] = $item->user->profile->title;
            $output[$k]['userid'] = $item->user_id;
            $output[$k]['user_username'] = $item->user->name;
        }
        return JsonBuilder::Success($output);
    }


    public function apply_add(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $day = date('Y-m-d', strtotime($request->get('day')));
        $time = date('H:i:s', strtotime($request->get('time')));
        $type = strip_tags($request->get('type'));
        $type = $type=='online'?1:2;
        $time_slot = intval($request->get('time_slot'));//1上午2下午3晚上
        $content = strip_tags($request->get('content'));
        $data = [
            'user_id' => $user->id,
            'manager_user_id' => 0,
            'attendance_date' => $day,
            'attendance_time' => $time,
            'type' => $type,
            'time_slot' => $time_slot,
            'school_id'=>$schoolId,
            'content' => $content,
            'status'  => 1
        ];
        $result = $dao->createMessage($data);
        if($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }

    }
    public function apply_list(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $result = $dao->getAttendanceMessages($user->id);
        $data = [];
        foreach ($result->items() as $key => $item){
            $data[$key]['approvesid'] = $item->id;
            $data[$key]['day'] = $item->attendance_date;
            $data[$key]['time'] = $item->attendance_time;
            $data[$key]['type'] = $item->type==1?'online':'offline';
            $data[$key]['time_slot'] = $item->time_slot;
            $data[$key]['create_time'] = $item->created_at;
            $data[$key]['status'] = $item->status;
            $data[$key]['group_name'] = $item->member->group->name;
        }
        return JsonBuilder::Success($data);
    }


    public function getGroupList(Request $request)
    {
        $user = $request->user();
        $memberObj = getMember($user->id);
        if ($memberObj->status!=2) {
            return JsonBuilder::Error('无权处理');
        }
        //TODO 有权限的用户才能访问
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $groups = $dao->getGroupList($schoolId);
        $output = [];
        foreach($groups as $k => $group) {
            $output[$k]['group_name'] = $group->name;
            $output[$k]['groupid'] = $group->id;
        }
        return JsonBuilder::Success($output);
    }

    /**
     * 请假申请
     * @param Request $request
     * @return string
     */
    public function takeLeave(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $data = [];
        $data['reason'] = strip_tags($request->get('reason', ''));
        $data['des']    = strip_tags($request->get('des', ''));
        $data['categoryid'] = intval($request->get('categoryid', 14));//14表示其他分类
        $data['start_time'] = date('Y-m-d H:is',intval($request->get('start_time')));
        $data['end_time']   = date('Y-m-d H:is',intval($request->get('end_time')));
        //处理基础数据
        $data['user_id']    = $user->id;
        $data['school_id']  = $schoolId;
        $data['type']       = OaAttendanceLeaveAndVisits::LEAVE_TYPE;
        //处理图片
        $data['files'] = $this->optPic($request);
        $result = $dao->createLeaveOrVisit($data);
        if ($result->getCode()==1000){
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error('请假申请保存失败请重试');
        }

    }

    /**
     * 外出申请
     * @param Request $request
     * @return string
     */
    public function addVisit(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $data = [];
        $data['reason']     = strip_tags($request->get('reason', ''));
        $data['des']        = strip_tags($request->get('des', ''));
        $data['address']    = strip_tags($request->get('address', ''));
        $data['categoryid'] = intval($request->get('categoryid', 6));//6表示外出
        $data['start_time'] = date('Y-m-d H:is',intval($request->get('start_time')));
        $data['end_time']   = date('Y-m-d H:is',intval($request->get('end_time')));
        //处理基础数据
        $data['user_id']    = $user->id;
        $data['school_id']  = $schoolId;
        $data['type']       = OaAttendanceLeaveAndVisits::VISIT_TYPE;
        //处理图片
        $data['files']      = $this->optPic($request);
        $result             = $dao->createLeaveOrVisit($data);
        if ($result->getCode()==1000){
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error('外出申请保存失败请重试');
        }

    }

    public function optPic($request)
    {
        $data = [];
        $fileConfig = config('filesystems.disks.public');
        $files = $request->allFiles();
        foreach ($files as $name => $file) {
            $path = 'attendance'.DIRECTORY_SEPARATOR.date('Ym');
            $realFileName = $file->store($path,'public');
            $ext = pathinfo($realFileName,PATHINFO_EXTENSION);
            if (in_array($ext, ['png','jpg','jpeg','bmp'])) {
                $data[$name] = $realFileName;
            }else{
                Storage::delete($fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName);
            }
        }
        return $data;
    }

    /**
     * 获取外出申请的分类
     * @param Request $request
     * @return string
     */
    public function get_visit_category(Request $request)
    {
        $data = [];
        $category = OaAttendanceLeaveAndVisits::VISIT_CATEGORY_MAP;
        $i = 0;
        foreach ($category as $id=>$name)
        {
            $data[$i]['categoryid'] = $id;
            $data[$i]['category_name'] = $name;
            $i++;
        }
        return JsonBuilder::Success($data);
    }

    /**
     * 获取请假申请的分类
     * @param Request $request
     * @return string
     */
    public function get_leave_category(Request $request)
    {

        $data = [];
        $category = OaAttendanceLeaveAndVisits::LEAVE_CATEGORY_MAP;
        $i = 0;
        foreach ($category as $id=>$name)
        {
            $data[$i]['categoryid'] = $id;
            $data[$i]['category_name'] = $name;
            $i++;
        }
        return JsonBuilder::Success($data);
    }
    public function my_list(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $status = intval($request->get('status'));

        $result = $dao->getLeaveOrVisitList($user->id,$schoolId,$status);
        return JsonBuilder::Success($result);
    }
    public function wait_list(Request $request)
    {
        $user = $request->user();
        $dao = new OaAttendanceTeacherDao();
        $memberObj = $dao->getMember($user->id);
        if ($memberObj->status!=2) {
            return JsonBuilder::Error('无权处理');
        }
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $postStatus = intval($request->get('status'));
        if ($postStatus==2) {
            $status = OaAttendanceLeaveAndVisits::STATUS_ACCEPT;
        } elseif($postStatus ==3) {
            $status = OaAttendanceLeaveAndVisits::STATUS_REJECT;
        } else {
            $status = OaAttendanceLeaveAndVisits::STATUS_DOING;
        }

        $result = $dao->getLeaveOrVisitListForManager($schoolId,$status);
        return JsonBuilder::Success($result);
    }
    public function approver_action(Request $request)
    {
        $id = intval($request->get('id'));
        $reply = strip_tags($request->get('not_pass_des'));
        $postStatus = intval($request->get('status'));
        if ($postStatus==2) {
            $status = OaAttendanceLeaveAndVisits::STATUS_ACCEPT;
        } elseif($postStatus ==3) {
            $status = OaAttendanceLeaveAndVisits::STATUS_REJECT;
        } else {
            return JsonBuilder::Error('处理失败');
        }
        $user = $request->user();
        $dao = new OaAttendanceTeacherDao();
        $memberObj = $dao->getMember($user->id);
        if ($memberObj->status!=2) {
            return JsonBuilder::Error('无权处理');
        }
        $schoolId = $user->getSchoolId();

        $result = $dao->approverAction($id,$schoolId, $user->id,$reply,$status);
        if ($result>0){
            return JsonBuilder::Success('处理成功');
        } else {
            return JsonBuilder::Error('处理失败');
        }
    }
    public function info(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $approversid = intval($request->get('approversid'));
        $obj = $dao->info($approversid,$schoolId);
        if ($obj->user_id != $user->id) {
            $memberObj = $dao->getMember($user->id);
            if ($memberObj->status!=2) {
                return JsonBuilder::Error('无权处理');
            }
        }
        return JsonBuilder::Success($obj);
    }


    public function getMonthManage(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $month = strip_tags($request->get('month'));
        $dao = new OaAttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $allMembers = $dao->getAllMembers($group->id);
        $result = $dao->getAllMonthList($schoolId,$month);
        $timeArr = $dao->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];

        $restList   = [];
        $missList   = [];
        $lateList   = [];
        $earlyList  = [];
        $okList     = [];
        $notList    = [];
        $daysCount     = 0;
        $normalCount   = 0;
        $restCount     = 0;
        $notCount      = 0;
        $lateCount     = 0;
        $earlyCount    = 0;
        $missCount     = 0;
        $approverCount = 0;

        foreach ($allMembers as $member)
        {
            $uidKey = $member->user_id;
            $days_count     = 0;
            $normal_count   = 0;
            $rest_count     = 0;
            $not_count      = 0;
            $late_count     = 0;
            $early_count    = 0;
            $miss_count     = 0;
            $approver_count = 0;
            $start = $timeArr['start'];
            $end   = $timeArr['end'];
            while ($start<$end)
            {
                //统计当月天数
                $days_count++;
                //统计休息日天数
                if (date('N',strtotime($start))>5) {
                    $rest_count++;
                }

                if (!isset($result[$uidKey][$start]) && date('N',strtotime($start))<=5) {
                    //旷工日统计
                    $not_count++;
                } elseif(isset($result[$start])) {
                    //出勤日统计
                    $normal_count++;
                    //迟到
                    if(strtotime($result[$uidKey][$start]->morning_online_mine) > strtotime($result[$uidKey][$start]->check_in_date.' '.$group->morning_online_time))
                    {
                        $late_count++;
                    }
                    if(strtotime($result[$uidKey][$start]->afternoon_online_mine) > strtotime($result[$uidKey][$start]->check_in_date.' '.$group->afternoon_online_time))
                    {
                        $late_count++;
                    }
                    if(strtotime($result[$uidKey][$start]->night_online_mine) > strtotime($result[$uidKey][$start]->check_in_date.' '.$group->night_online_time))
                    {
                        $late_count++;
                    }
                    //补卡
                    if($result[$member][$start]->status == 2) {
                        $approver_count++;
                    }

                    //早退
                    if (strtotime($result[$uidKey][$start]->morning_offline_mine) < strtotime($result[$uidKey][$start]->check_in_date.' '.$group->morning_offline_time))
                    {
                        $early_count++;
                    }
                    if (strtotime($result[$uidKey][$start]->afternoon_offline_mine) < strtotime($result[$uidKey][$start]->check_in_date.' '.$group->afternoon_offline_time))
                    {
                        $early_count++;
                    }
                    if (strtotime($result[$uidKey][$start]->night_offline_mine) < strtotime($result[$uidKey][$start]->check_in_date.' '.$group->night_offline_time))
                    {
                        $early_count++;
                    }
                    //缺卡记录
                    if (empty($result[$uidKey][$start]->morning_online_mine)) {
                        $miss_count++;
                    } elseif(empty($result[$uidKey][$start]->morning_offline_mine)) {
                        $miss_count++;
                    }
                    if (empty($result[$uidKey][$start]->afternoon_online_mine)) {
                        $miss_count++;
                    } elseif(empty($result[$uidKey][$start]->afternoon_offline_mine)) {
                        $miss_count++;
                    }
                    if (empty($result[$uidKey][$start]->night_online_mine)) {
                        $miss_count++;
                    } elseif(empty($result[$uidKey][$start]->night_offline_mine)) {
                        $miss_count++;
                    }

                }
                $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
            }
            $memberArr = [
                'userid'        => $member->user_id,
                'user_pics'     => $member->user->profile->avatar,
                'user_username' => $member->user->name,
                //'schooltwo_name'=> $member->user->gradeUser->department->name,//实际上教师不会关联到某个系
                'count'         => 0,
                'transTime'     => 1,
            ];
            $daysCount     =  $days_count;
            $normalCount   += $normal_count;
            $restCount     += $rest_count;
            $notCount      += $not_count;
            $lateCount     += $late_count;
            $earlyCount    += $early_count;
            $missCount     += $miss_count;

            if ($rest_count > 0)
            {
                $memberArr['count'] = $rest_count;
                $restList[] = $memberArr;
            }
            if ($miss_count > 0)
            {
                $memberArr['count'] = $miss_count;
                $missList[] = $memberArr;
            }
            if ($late_count > 0)
            {
                $memberArr['count'] = $late_count;
                $lateList[] = $memberArr;
            }
            if ($early_count > 0)
            {
                $memberArr['count'] = $early_count;
                $earlyList[] = $memberArr;
            }
            if ($normal_count > 0)
            {
                $memberArr['count'] = $normal_count;
                $okList[] = $memberArr;
            }
            if ($not_count > 0)
            {
                $memberArr['count'] = $not_count;
                $notList[] = $memberArr;
            }


        }

        $leaveCollect = $dao->getLeaveOrVisitListByTime($schoolId, OaAttendanceLeaveAndVisits::LEAVE_TYPE, $start, $end);
        $awayCollect  = $dao->getLeaveOrVisitListByTime($schoolId, OaAttendanceLeaveAndVisits::VISIT_TYPE, $start, $end);


        $data = [
            'groupid'   => $group->id,
            'month'     => $month,
            'all'       => $daysCount,
            'leave'     => $leaveCollect->count(),
            'away'      => $awayCollect->count(),
            'not'       => $notCount,
            'late'      => $lateCount,
            'early'     => $earlyCount,
            'ok'        => $normalCount,
            'miss'      => $missCount,
            'rest'      => $restCount,
            'leaveList' => $this->_getList($leaveCollect),
            'awayList'  => $this->_getList($awayCollect),
            'notList'   => $notList,
            'lateList'  => $lateList,
            'earlyList' => $earlyList,
            'okList'    => $okList,
            'missList'  => $missList,
            'restList'  => $restList,
        ];
        return JsonBuilder::Success($data);
    }

    private function _getList($collect)
    {
        $list  = [];

        foreach ($collect as $k => $v) {
            $list[$v->user_id]['userid']        = $v->user_id;
            $list[$v->user_id]['user_pics']     = $v->user->profile->avatar;
            $list[$v->user_id]['user_username'] = $v->user->name;
            $list[$v->user_id]['schooltwo_name']= $v->user->gradeUser->department->name;
            $list[$v->user_id]['count']         += 1;
            $list[$v->user_id]['transTime']     = 1;
        }
        $output = [];
        foreach ($list as $value)
        {
            $output[] = $value;
        }
        return $output;
    }
}
