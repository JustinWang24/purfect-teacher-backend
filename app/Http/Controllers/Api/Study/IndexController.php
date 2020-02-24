<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/6
 * Time: 下午1:52
 */

namespace App\Http\Controllers\Api\Study;


use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Schools\SchoolDao;
use App\Dao\Courses\CourseMajorDao;
use App\Http\Controllers\Controller;
use App\Models\Courses\CourseMaterial;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Courses\Lectures\LectureDao;
use App\Http\Requests\MyStandardRequest;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;

class IndexController extends Controller
{
    public function index(MyStandardRequest $request) {
        $user = $request->user();

        $selectCourse = [
            'status' => 'true', // true 开启 false 关闭
            'time' => '2020年1月3日-2020年1月15日',
            'msg' => '大家选课期间请看好选课程对应的学分',
        ];


        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        // todo 时间暂时写死
        $date = Carbon::now()->toDateString();
        $date = Carbon::parse('2020-01-08 14:40:00');;
        $year = $configuration->getSchoolYear($date);
        $month = Carbon::parse($date)->month;
        $term = $configuration->guessTerm($month);
        $timetableItemDao = new TimetableItemDao();
        $item = $timetableItemDao->getCurrentItemByUser($user);

        $timetable = (object)[];
        $attendancesDetailsDao = new AttendancesDetailsDao();

        $signIn = [
            'status' => 0,
            'signIn_num' => $attendancesDetailsDao->getSignInCountByUser($user->id, $year, $term),
            'leave_num' => $attendancesDetailsDao->getLeaveCountByUser($user->id, $year, $term),
            'truant_num' => $attendancesDetailsDao->getTruantCountByUser($user->id, $year, $term),
        ];


        $evaluateTeacher = false;
        if(!is_null($item)) {

            $weeks = $configuration->getScheduleWeek(Carbon::parse($date), null, $term);
            $week = $weeks->getScheduleWeekIndex();

            $course = $item->course;
            $materials = $course->materials;
            $types = array_column($materials->toArray(), 'type');
            $label = [];
            foreach ($types as $key => $val) {
                $label[] = CourseMaterial::GetTypeText($val);
            }

            $timetable = [
                'time_slot' => $item->timeSlot->name,
                'time' => $item->timeSlot->from.'-'.$item->timeSlot->to,
                'label' => $label,
                'course' => $course->name,
                'room' => $item->room->name,
                'teacher' => $item->teacher->name,
                'week' => $week,
                'item_id'=> $item->id,
            ];

            $weeks = $configuration->getScheduleWeek(Carbon::parse($date), null, $term);

            $week = $weeks->getScheduleWeekIndex() ?? '';

            $attendancesDao = new AttendancesDao();

            $attendance = $attendancesDao->getAttendanceByTimeTableId($item->id,$week);


            $detail = $attendancesDetailsDao->getDetailByUserId($user->id, $attendance->id);

            $signIn['status'] = $detail->mold ?? 0;
            $evaluateTeacher = true;
        }

        $gradeId = $user->gradeUser->grade_id;
        $dao = new LectureDao();
        $material = $dao->getMaterialByGradeId($gradeId);
        $studyData = $material? $material->url : '';
        $data = [
            'selectCourse' => $selectCourse, // 选课
            'timetable' => $timetable,  // 课程
            'studyData' => $studyData, // 学习资料
            'signIn'=> $signIn, // 签到
            'evaluateTeacher' => $evaluateTeacher // 评教 true false
        ];
        return JsonBuilder::Success($data);
    }


    /**
     * 课件类型列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function materialType(MyStandardRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new LectureDao();
        $list = $dao->getMaterialType($schoolId);
        return JsonBuilder::Success($list);
    }


    /**
     * 教材资料
     * @param MyStandardRequest $request
     * @return string
     */
    public function materialList(MyStandardRequest $request) {
        $type = $request->get('type_id');
        if(is_null($type)) {
            return JsonBuilder::Error('缺少参数');
        }
        $keyword = $request->get('keyword');
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        // todo 时间暂时写死
        $date = Carbon::now()->toDateString();
        $date = Carbon::parse('2020-01-08 14:40:00');;
        $year = $configuration->getSchoolYear($date);
        $month = Carbon::parse($date)->month;
        $term = $configuration->guessTerm($month);

        $gradeId = $user->gradeUser->grade_id;
        $courseMajors = $user->gradeUser->major->courseMajors;
        if(count($courseMajors) == 0) {
            return JsonBuilder::Error('您没有课程');
        }
        $courseIds = $courseMajors->pluck('course_id')->toArray();
        $material = [];
        if(!is_null($keyword)) {
            // 根据资料名称搜索
            $material = $this->getMaterial($courseIds,$keyword, $gradeId, $year, $term, $type);
            $courseMajorDao = new CourseMajorDao();
            $courseMajors = $courseMajorDao->getCourseMajorByCourseIdsAndCourseName($courseIds, $keyword);
            $courseIds = $courseMajors->pluck('course_id')->toArray();

        }

        $itemDao = new TimetableItemDao();
        $timetable = $itemDao->getGradeTeachersByCoursesId($courseIds, $gradeId, $year, $term);
        $list = [];
        $dao = new LectureDao();

        foreach ($timetable as $key => $item) {
            $list[] = $dao->getMaterialsByType($item->course_id,$gradeId,$item->teacher_id,$type);
        }

        $list = array_merge($list, $material);

        $data = [];
        foreach ($list as $key => $item) {
            foreach ($item as $k => $val) {

                $data[$val->course_id]['course'] = $val->course->name;

                $data[$val->course_id]['list'][] = [
                    'id' => $val->id,
                    'type' => $val->media->getTypeText(),
                    'file_name' => $val->media->file_name,
                    'keyword' => $val->description,
                    'url' => $val->url,
                    'created_at' => $val->created_at
                ];
            }
        }
        $result = array_merge($data);
        foreach ($result as $key => $item) {
            $result[$key]['list'] = $this->second_array_unique_bykey($item['list'], 'id');
        }
        return JsonBuilder::Success($result);
    }


    /**
     * 根据资料名称搜索
     * @param $courseIds
     * @param $keyword
     * @param $gradeId
     * @param $year
     * @param $term
     * @param $type
     * @return array
     */
    public function getMaterial($courseIds, $keyword,$gradeId, $year, $term, $type) {

        $itemDao = new TimetableItemDao();
        $timetable = $itemDao->getGradeTeachersByCoursesId($courseIds, $gradeId, $year, $term);
        $list = [];
        $dao = new LectureDao();

        foreach ($timetable as $key => $item) {
            $list[] = $dao->getMaterialsByType($item->course_id,$gradeId,$item->teacher_id,$type, $keyword);
        }
        return $list;
    }


    /**
     * 二维数组去重
     * @param $arr
     * @param $key
     * @return mixed
     */
    public function second_array_unique_bykey($arr, $key){
        $tmp_arr = array();
        foreach($arr as $k => $v) {

            if(in_array($v[$key], $tmp_arr)) {
                //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
            }
            else {
                $tmp_arr[$k] = $v[$key];  //将不同的值放在该数组中保存
            }
        }
        return $arr;
    }


    /**
     * 学习资料课程列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function courseList(MyStandardRequest $request) {
        $userId = $request->user()->id;
        $dao = new LectureDao();
        $lectures = $dao->getMaterialByTeacherId($userId);
        $courses = [];
        foreach ($lectures as $key => $item) {
            $courses[$key] = [
                'course_id' => $item->course_id,
                'course_name' => $item->course->name,
            ];
        }

        return JsonBuilder::Success($courses);
    }


    /**
     * 课程资料列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function courseMaterialList(MyStandardRequest $request) {
        $userId = $request->user()->id;
        $courseId = $request->getCourseId();
        $type = $request->get('type_id');
        if(empty($courseId) || empty($type)) {
            return JsonBuilder::Error('缺少参数');
        }

        $dao = new LectureDao();
        $return = $dao->getMaterialByCourseId($courseId, $type, $userId);
        $result = pageReturn($return);

        foreach ($result['list'] as $key => $item) {
            $result['list'][$key] = [
                'id' => $item->id,
                'type' => $item->media->getTypeText(),
                'file_name' => $item->media->file_name,
                'url' => $item->url,
            ];
        }

        return JsonBuilder::Success($result);
    }

}
