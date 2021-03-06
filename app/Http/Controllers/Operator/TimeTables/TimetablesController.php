<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 3:19 PM
 */

namespace App\Http\Controllers\Operator\TimeTables;
use App\Dao\Courses\CourseDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\RoomDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeTable\TimetableRequest;
use App\Dao\Schools\SchoolDao;
use App\Utils\Time\CalendarWeek;
use Carbon\Carbon;

class TimetablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 课程表管理的默认首页
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager(TimetableRequest $request){
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());
        $this->dataForView['pageTitle'] = $school->name . ' 上课时间段管理';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['school'] = $school;
        $this->dataForView['config'] = $school->configuration;
        $this->dataForView['app_name'] = 'time_slots_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * 添加课程表项的视图, 同时可以预览课程表
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preview(TimetableRequest $request){
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());
        $this->dataForView['pageTitle'] = $school->name . ' 课程表管理';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['school'] = $school;
        $this->dataForView['app_name'] = 'timetable_preview_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * 查看某个班的课程表
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_grade_timetable(TimetableRequest $request){
        // 这是一个单独的页面, 不是课程表管理页面
        $gradeDao = new GradeDao($request->user());
        $grade = $gradeDao->getGradeById($request->uuid());
        $school = $grade->school;
        $year = $school->configuration->getSchoolYear();
        $month = Carbon::now()->month;
        $term = $school->configuration->guessTerm($month);

        $this->dataForView['pageTitle'] = $grade->name . ' 课程表';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['grade'] = $grade;
        $this->dataForView['school'] = $school;

        $this->dataForView['year'] = $year; // 默认加载第一学期
        $this->dataForView['term'] = $term; // 默认加载第一学期
        $this->dataForView['app_name'] = 'timetable_grade_view_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * 查看某课程的课程表
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_course_timetable(TimetableRequest $request){
        // 这是一个单独的页面, 不是课程表管理页面
        $courseDao = new CourseDao();
        $course = $courseDao->getCourseByIdOrUuid($request->uuid());

        $this->dataForView['pageTitle'] = $course->name . ' 的课程安排';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['course'] = $course;
        $this->dataForView['school'] = $course->school;
        $this->dataForView['term'] = $request->has('term') ? $request->get('term') : 1; // 默认加载第一学期
        $this->dataForView['app_name'] = 'timetable_course_view_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * 从授课老师的角度, 查看自己的课程表
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_teacher_timetable(TimetableRequest $request){
        // 这是一个单独的页面, 不是课程表管理页面
        $userDao = new UserDao();
        /**
         * @var \App\User $teacher
         */
        $teacher = $userDao->getUserByIdOrUuid($request->uuid());

        $this->dataForView['pageTitle'] = '教师: ' . $teacher->name . ' 的排课';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['teacher'] = $teacher;
        $this->dataForView['school'] = $teacher->gradeUser->school;
        $this->dataForView['term'] = $request->has('term') ? $request->get('term') : 1; // 默认加载第一学期
        $this->dataForView['app_name'] = 'timetable_teacher_view_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_room_timetable(TimetableRequest $request){
        // 这是一个单独的页面, 不是课程表管理页面
        $roomDao = new RoomDao($request->user());
        /**
         * @var \App\User $teacher
         */
        $room = $roomDao->getRoomById($request->uuid());

        $this->dataForView['pageTitle'] = $room->building->name .' - 教室: ' . $room->name . ' 的排课';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['room'] = $room;
        $this->dataForView['school'] = $room->building->school;
        $this->dataForView['term'] = $request->has('term') ? $request->get('term') : 1; // 默认加载第一学期
        $this->dataForView['app_name'] = 'timetable_room_view_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }
}