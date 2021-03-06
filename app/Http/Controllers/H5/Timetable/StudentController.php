<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/12/19
 * Time: 7:32 PM
 */

namespace App\Http\Controllers\H5\Timetable;
use App\BusinessLogic\TimetableViewLogic\Factory;
use App\Dao\Evaluation\RateTeacherDao;
use App\Dao\Students\LearningNoteDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Controllers\Controller;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * 学生查看自己今天课表的 H5 页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request){

        $user = $request->user('api');

        $this->dataForView['user'] = $user;

        $logic = Factory::GetInstance($request);
        $this->dataForView['today'] = $logic->build();

        $this->dataForView['pageTitle'] = '我的课程表';
        $this->dataForView['type'] = $request->get('type','daily');
        $this->dataForView['day'] = $request->get('day',Carbon::now(GradeAndYearUtil::TIMEZONE_CN)->format('Y-m-d'));
        $this->dataForView['api_token'] = $request->get('api_token');
        $this->dataForView['appName'] = 'timetable-student-view';
        return view('h5_apps.timetable.student_view', $this->dataForView);
    }

    /**
     * 课程详情页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request){
        $user = $request->user('api');
        $dao = new TimetableItemDao();
        $timeSlotItem = $dao->getItemById($request->get('item'), true);

        $this->dataForView['pageTitle'] = '我的课程表';
        $this->dataForView['timeSlotItem'] = $timeSlotItem;
        $this->dataForView['materials'] = []; // 老师对这堂课上传的课件, 讲义, 作业等

        $this->dataForView['type'] = $request->get('type');
        $this->dataForView['day'] = $request->get('day',Carbon::now(GradeAndYearUtil::TIMEZONE_CN)->format('Y-m-d'));
        $this->dataForView['api_token'] = $request->get('api_token');
        $this->dataForView['appName'] = 'timetable-student-detail';

        // 获取学生对本堂课的评价
        $rateDao = new RateTeacherDao();
        $this->dataForView['rate'] = $rateDao->getByTimetableItemAndUser($timeSlotItem->id, $user->id);
        // 获取教师的总体评价
        $this->dataForView['rateSummary'] = $rateDao->getSummaryByTeacher($timeSlotItem->teacher->id);

        // 学生的笔记
        $this->dataForView['notes'] = (new LearningNoteDao())->getByStudentAndTimetableItem($user, $timeSlotItem);
        return view('h5_apps.timetable.student_detail', $this->dataForView);
    }
}
