<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


use App\Dao\AttendanceSchedules\AttendanceCourseTeacherDao;
use App\Http\Requests\MyStandardRequest;
use App\Models\AttendanceSchedules\AttendanceCourseTeacher;
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
    public function signInRecord(AttendanceRequest $request) {

        $user = $request->user();
        $grade = $user->gradeUser->grade;
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        // 学年
        $year = $request->get('year',$configuration->getSchoolYear());
        // 学期
        $term = $request->get('term',$configuration->guessTerm(Carbon::now()->month));

        $courseMajorDao = new CourseMajorDao();
        $attendancesDetailsDao = new AttendancesDetailsDao();
        $courseList = $courseMajorDao->getCoursesByMajorAndYear($grade->major_id, $grade->gradeYear());
        foreach ($courseList as $key => $val) {

            // 签到次数
            $signNum = $attendancesDetailsDao->getSignInCountByUser($user->id, $year,$term, $val['id']);
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
    public function signInDetails(AttendanceRequest $request) {

        $courseId = $request->get('course_id');
        if(empty($courseId)) {
            return JsonBuilder::Error('缺少参数');
        }

        $user = $request->user();
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        $year = $request->get('year',$configuration->getSchoolYear());
        // 学期
        $term = $request->get('term',$configuration->guessTerm(Carbon::now()->month));

        $attendancesDetailsDao = new AttendancesDetailsDao();
        $signInList = $attendancesDetailsDao->signInList($year, $user->id, $courseId, $term);
        foreach ($signInList as $key => $val) {
            $signInList[$key]['time_slots'] = $val->timetable->timeSlot->name;
            $signInList[$key]['weekday_index'] = $val->timetable->weekday_index;
            $signInList[$key]['time'] = Carbon::parse($val->date)->format('H:i');
            $signInList[$key]['date'] = Carbon::parse($val->date)->format('Y-m-d');

            // 判断请假的
            if($val['mold'] == AttendancesDetail::MOLD_LEAVE) {
                if($val['status'] !== AttendancesDetail::STATUS_CONSENT) {
                    unset($signInList[$key]);
                }
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
    public function addTruantRecord(AttendanceRequest $request) {

        $truant = $request->getTruantData();
        $timeTableDao = new TimetableItemDao();
        $item = $timeTableDao->getItemById($truant['timetable_id']);
        $data = Carbon::parse($truant['date']);
        $week = $item->school->configuration->getScheduleWeek($data)->getScheduleWeekIndex();

        $attendanceDao = new AttendancesDao();
        $attendanceInfo = $attendanceDao->getAttendanceByTimeTableId($item->id, $week);
        if(is_null($attendanceInfo)) {
            return JsonBuilder::Error('该课程还没上');
        }
        $truant['attendance_id'] = $attendanceInfo->id;
        $truant['course_id']     = $item->course_id;
        $truant['year']          = $item->year;
        $truant['term']          = $item->term;
        $truant['week']          = $week;
        $truant['mold']          = AttendancesDetail::MOLD_TRUANT;
        $truant['weekday_index'] = $item->weekday_index;
        $dao = new AttendancesDetailsDao();
        $re = $dao->getDetailByUserId($truant['student_id'],$item->id);
        if(!empty($re)) {
            return JsonBuilder::Success('旷课已添加');
        }
        $result = $dao->add($truant);
        if($result) {
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
            return  JsonBuilder::Error('修改失败');
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
        $arriveTime = '';
        if ($arrive) {
            $arriveTime = $arrive->teacher_sign_time;
        }
        $data['timetable_id'] = $items[0]->id;
        $data['time_slot_name'] = $items[0]->timeSlot->name;
        $data['course_name'] = $items[0]->course->name;
        $data['teacher'] = $items[0]->teacher->name;
        $data['room'] = $items[0]->room->name;
        $data['is_arrive'] =  !empty($arrive) ? 'true': 'false';
        $data['arrive_time'] = $arriveTime;

        return  JsonBuilder::Success($data);
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
        $data = $dao->updateTeacherSignByItem($result->id);
        if ($data) {
            return JsonBuilder::Success('签到成功');
        } else {
            return JsonBuilder::Error('签到失败');
        }
    }
}
