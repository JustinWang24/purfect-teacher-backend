<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


use App\Dao\AttendanceSchedules\AttendanceCourseTeacherDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimeSlotDao;
use App\Http\Requests\MyStandardRequest;
use App\Models\AttendanceSchedules\Attendance;
use App\Models\AttendanceSchedules\AttendanceCourseTeacher;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Courses\CourseMajorDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Http\Requests\AttendanceSchedule\AttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * 课程签到列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function signInRecord(AttendanceRequest $request)
    {

        $user = $request->user();
        $grade = $user->gradeUser->grade;
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        // 学年
        $year = $request->get('year', $configuration->getSchoolYear());
        // 学期
        $term = $request->get('term', $configuration->guessTerm(Carbon::now()->month));
        $gradeYear = $year - $grade->year + 1; // 年级

        $courseMajorDao = new CourseMajorDao();
        $attendancesDetailsDao = new AttendancesDetailsDao();
        $courseList = $courseMajorDao->getCoursesByMajorAndYear($grade->major_id, $gradeYear, $term);
        foreach ($courseList as $key => $val) {

            // 签到次数
            $signNum = $attendancesDetailsDao->getSignInCountByUser($user->id, $year, $term, $val['id']);
            // 请假次数
            $leavesNum = $attendancesDetailsDao->getLeaveCountByUser($user->id, $year, $term, $val['id']);
            // 旷课次数
            $truantNum = $attendancesDetailsDao->getTruantCountByUser($user->id, $year, $term, $val['id']);
            $courseList[$key]['sign_num'] = $signNum;
            $courseList[$key]['leaves_num'] = $leavesNum;
            $courseList[$key]['truant_num'] = $truantNum;
        }

        return JsonBuilder::Success($courseList);
    }


    /**
     * 签到详情列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function signInDetails(AttendanceRequest $request)
    {

        $courseId = $request->get('course_id');
        if (empty($courseId)) {
            return JsonBuilder::Error('缺少参数');
        }

        $user = $request->user();
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        $year = $request->get('year', $configuration->getSchoolYear());
        // 学期
        $term = $request->get('term', $configuration->guessTerm(Carbon::now()->month));

        $attendancesDetailsDao = new AttendancesDetailsDao();
        $signInList = $attendancesDetailsDao->signInList($year, $user->id, $courseId, $term);
        foreach ($signInList as $key => $val) {

            $signInList[$key]['time_slots'] = $val->timetable->timeSlot->name;
            $signInList[$key]['weekday_index'] = $val->timetable->weekday_index;
            $signInList[$key]['date'] = Carbon::parse($val->created_at)->format('Y-m-d');
            $signInList[$key]['time'] = '';

            // 判断请假的
            if ($val['mold'] == AttendancesDetail::MOLD_LEAVE) {
                if ($val['status'] !== AttendancesDetail::STATUS_CONSENT) {
                    $signInList[$key]['mold'] = AttendancesDetail::MOLD_TRUANT;
                }
            }
            if($val['mold'] == AttendancesDetail::MOLD_SIGN_IN) {
                $signInList[$key]['time'] = Carbon::parse($val->created_at)->format('H:i');

            }
            unset($val->status);
            unset($val->timetable);
            unset($val->timetable_id);
        }
        $data = array_merge($signInList->toArray());
        return JsonBuilder::Success($data);
    }


    /**
     * 添加旷课记录
     * @param AttendanceRequest $request
     * @return string
     */
    public function addTruantRecord(AttendanceRequest $request)
    {

        $truant = $request->getTruantData();
        $timeTableDao = new TimetableItemDao();
        $item = $timeTableDao->getItemById($truant['timetable_id']);
        $data = Carbon::parse($truant['date']);
        $week = $item->school->configuration->getScheduleWeek($data)->getScheduleWeekIndex();

        $attendanceDao = new AttendancesDao();
        $attendanceInfo = $attendanceDao->getAttendanceByTimeTableId($item->id, $week);
        if (is_null($attendanceInfo)) {
            return JsonBuilder::Error('该课程还没上');
        }
        $truant['attendance_id'] = $attendanceInfo->id;
        $truant['course_id'] = $item->course_id;
        $truant['year'] = $item->year;
        $truant['term'] = $item->term;
        $truant['week'] = $week;
        $truant['mold'] = AttendancesDetail::MOLD_TRUANT;
        $truant['weekday_index'] = $item->weekday_index;
        $dao = new AttendancesDetailsDao();
        $re = $dao->getDetailByUserId($truant['student_id'], $attendanceInfo->id);
        if (!empty($re)) {
            return JsonBuilder::Success('旷课已添加');
        }
        $result = $dao->add($truant);
        if ($result) {
            return JsonBuilder::Success('旷课添加成功');
        } else {
            return JsonBuilder::Error('旷课添加失败');
        }
    }


    /**
     * 开启补签
     * @param MyStandardRequest $request
     * @return string
     */
    public function startSupplement(MyStandardRequest $request)
    {
        $attendanceId = $request->get('attendance_id');
        $type = $request->get('type');

        $dao = new AttendancesDao;

        $result = $dao->update($attendanceId, ['supplement_sign' => $type]);
        if ($result) {
            return JsonBuilder::Success('修改成功');
        } else {
            return JsonBuilder::Error('修改失败');
        }
    }

    /**
     * 教师扫码云班牌
     * @param MyStandardRequest $request
     * @return string
     */
    public function teacherSweepQrCode(MyStandardRequest $request)
    {
        $user = $request->user();
        $code = json_decode($request->get('code'), true);

        if ($code['teacher_id'] !== $user->id) {
            return JsonBuilder::Error('本节课, 不是您要上的课');
        }

        $timeTableDao = new TimetableItemDao;
        // 同时上多个课程 只取第一个
        $items = $timeTableDao->getCurrentItemByUser($user);
        if ($items->isEmpty()) {
            return JsonBuilder::Error('未找到您目前要上的课程');
        }
        $attendancesDao = new AttendancesDao;
        $arrive = $attendancesDao->getTeacherIsSignByItem($items[0], $user);
        if (is_null($arrive)) {
            $createData = $attendancesDao->createAttendanceData($items[0]);
            if (!$createData) {
                return JsonBuilder::Error('生成签到初始化数据失败');
            }
        }

        if ($arrive->teacher_sign == Attendance::TEACHER_SIGN) {
            $isArrive = true;
        } else {
            $isArrive = false;
        }

        $arriveTime = '';
        if (!empty($arrive->teacher_sign_time)) {
            $arriveTime = $arrive->teacher_sign_time;
        }

        $data['timetable_id'] = $items[0]->id;
        $data['time_slot_name'] = $items[0]->timeSlot->name;
        $data['course_name'] = $items[0]->course->name;
        $data['teacher'] = $items[0]->teacher->name;
        $data['room'] = $items[0]->room->name;
        $data['is_arrive'] = $isArrive;
        $data['arrive_time'] = $arriveTime;

        return JsonBuilder::Success($data);
    }

    /**
     * 教师上课签到
     * @param MyStandardRequest $request
     * @return string
     */
    public function teacherSign(MyStandardRequest $request)
    {
        $user = $request->user();

        $timetableItemDao = new TimetableItemDao;
        $item = $timetableItemDao->getCurrentItemByUser($user);
        if (empty($item)) {
            return JsonBuilder::Error('未找到该老师目前上的课程');
        }

        $dao = new AttendancesDao;
        $result = $dao->getTeacherIsSignByItem($item[0], $user);
        if (is_null($result)) {
            $result = $dao->createAttendanceData($item[0]);
        }

        $courseTime = $item[0]->timeSlot->from;
        $date = Carbon::now()->toTimeString();
        if($date > $courseTime) {
            $late = Attendance::TEACHER_LATE;
        } else {
            $late = Attendance::TEACHER_NO_LATE;
        }

        $data = $dao->updateTeacherSignByItem($result->id, $late);
        if ($data) {
            return JsonBuilder::Success('签到成功');
        } else {
            return JsonBuilder::Error('签到失败');
        }
    }


    /**
     * 学生扫码云班牌
     * @param MyStandardRequest $request
     * @return string
     */
    public function studentSweepQrCode(MyStandardRequest $request)
    {
        $code = json_decode($request->get('code'), true);
        $user = $request->user();

        $timetableItemDao = new TimetableItemDao;
        $item = $timetableItemDao->getCurrentItemByUser($user);

        if (is_null($item)) {
            return JsonBuilder::Error('未找到当前学生要上的的课程');
        }

        $attendancesDetailsDao = new AttendancesDetailsDao;
        $isArrive = $attendancesDetailsDao->getDetailByTimeTableIdAndStudentId($item, $user);

        $data['timetable_id'] = $item->id;
        $data['time_slot_name'] = $item->timeSlot->name;
        $data['course_name'] = $item->course->name;
        $data['room'] = $item->room->name;
        $data['is_arrive'] = empty($isArrive) ? false: true;
        $data['arrive_time'] = '';
        $data['arrive_type'] = '';
        if(!empty($isArrive)) {
            $data['arrive_time'] = $isArrive->created_at->format('Y-m-d H:i');
            $data['arrive_type'] = $isArrive->typeText();
        }

        return  JsonBuilder::Success($data);
    }

    /**
     * 教师考勤 -获取当天所有课节
     * @param MyStandardRequest $request
     * @return string
     */
    public function getDayCourse(MyStandardRequest $request)
    {
        $user = $request->user();
        $time = $request->get('time');

        $timeSlotDao = new TimeSlotDao;
        $data =  $timeSlotDao->getAllStudyTimeSlots($user->getSchoolId());

        $result = [];
        if ($data) {
            foreach ($data as $key => $val) {
                $result[$key]['id'] = $val->id;
                $result[$key]['name'] = $val->name;
            }
            array_unshift($result,['id' => 0, 'name' => '全部']);
        }

        return JsonBuilder::Success($result);
    }


    /**
     * 教师考勤- 老师上课统计
     * @param MyStandardRequest $request
     * @return string
     */
    public function getTeacherCourseStatistics(MyStandardRequest $request)
    {
        $user = $request->user();
        $time = $request->get('time');
        $timeSlotId = $request->get('time_slot_id');

        $attendancesDao = new AttendancesDao;
        $timeTableDao = new TimetableItemDao;

        $now = Carbon::parse($time);
        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($user->getSchoolId());
        $configuration = $school->configuration;
        $year =  $configuration->getSchoolYear();
        $month = Carbon::parse($now)->month;
        $term = $configuration->guessTerm($month);

        $weekDayIndex = $now->dayOfWeekIso;
        $data = $attendancesDao->getTeacherSignInfoByTime($now, $timeSlotId);

        $new = [];
        foreach ($data as $key => $val) {
            $new[$val->time_slot_id][] = $val;
        }
        $result = [];
        $sign = 0;
        $late = 0;
        foreach ($new as $key => $value) {
          $sum = $timeTableDao->getSameTimePeopleNum($user->getSchoolId(), $year, $term, $weekDayIndex, $key);
          foreach ($value as $k => $v) {
            if ($v->teacher_sign == Attendance::TEACHER_SIGN) {
                $sign +=1;
            }

            if ($v->teacher_late == Attendance::TEACHER_LATE) {
                $late +=1;
            }

            $result[$key]['time_slot_id'] = $v->time_slot_id;
            $result[$key]['course'] = $v->timeSlot->name;
            $result[$key]['sign'] = $sign;
            $result[$key]['no_sign'] = $sum - $sign;
            $result[$key]['late'] = $late;
          }
        }
        sort($result);
        return JsonBuilder::Success(array_merge($result));
    }

    /**
     * 教师签到详情
     * @param MyStandardRequest $request
     * @return string
     */
    public function teacherSignDetails(MyStandardRequest $request)
    {
        $user = $request->user();
        $time = $request->get('time');
        $timeSlot = $request->get('time_slot_id');
        $type = $request->get('type');

        $timeTableDao = new TimetableItemDao;
        $item = $timeTableDao->getTimetableItemByUserOrTime($user, $time, [$timeSlot]);

        $itemIds = $item->pluck('id');

        $now = Carbon::parse($time);

        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($user->getSchoolId());
        $configuration = $school->configuration;

        $date = Carbon::now()->toDateString();
        $month = Carbon::parse($date)->month;
        $term = $configuration->guessTerm($month);
        $weeks = $configuration->getScheduleWeek($now, null, $term);
        $week = $weeks->getScheduleWeekIndex();
        $dao = new AttendancesDao;

        $data = $dao->getTeacherSignInfo($itemIds, $week, $type);

        $result = [];
        foreach ($data as $key => $val) {
            $result[$key]['avatar'] = $val->user->profile->avatar;
            $result[$key]['name']  = $val->teacher->name;
            $result[$key]['major']  = $val->gradeUser->major->name ?? '';
            $result[$key]['sign_status'] = '';
            $result[$key]['sign_time'] = '';
            if ($type == Attendance::TEACHER_SIGN) {
                $result[$key]['sign_status'] = $val->teacher_late == Attendance::TEACHER_NO_LATE ? '正常' : '迟到';
                $result[$key]['sign_time'] = $val->teacher_sign_time;
            }
        }
        return JsonBuilder::Success($result);
    }




}
