<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 19/12/19
 * Time: 8:39 PM
 */

namespace App\Http\Controllers\H5\Teacher;


use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\Calendar\CalendarDao;
use App\Dao\Notice\NoticeDao;
use App\Dao\Schools\NewsDao;
use App\Dao\Schools\SchoolDao;
use App\Http\Controllers\Controller;
use App\Models\Schools\News;
use App\Models\Schools\SchoolConfiguration;
use App\User;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request){
        $type = $request->get('type');
        /**
         * @var User $teacher
         */
        $teacher = $request->user('api');
        $this->dataForView['api_token'] = $request->get('api_token');
        $this->dataForView['teacher'] = $teacher;
        $school = (new SchoolDao())->getSchoolById($teacher->getSchoolId());
        $this->dataForView['school'] = $school;
        $this->dataForView['pageTitle'] = $type;


        $viewPath = 'h5_apps.teacher.news';

        switch ($type){
            case '作息时间':
                $viewPath = 'h5_apps.teacher.time_slots';
                $now = now(GradeAndYearUtil::TIMEZONE_CN);
                $this->dataForView['season'] = $now->between(
                    $school->configuration->summer_start_date,
                    $school->configuration->winter_start_date
                ) ? '夏季/秋季' : '冬季/春季';
                break;
            case '通讯录':
                $viewPath = 'h5_apps.teacher.contact';
                break;
            case '通知公告':
                $viewPath = 'h5_apps.teacher.notice';
                break;
            case '校历':
                $viewPath = 'h5_apps.teacher.calendar';
                $data = $this->loadEvents($school->id, $school->configuration);
                $this->dataForView['events'] = $data['events'];
                $this->dataForView['tags'] = $data['tags'];
                break;
            case '值班':
                $data = $this->loadAttendances($school->id, $school->configuration);
                $this->dataForView['attendances'] = $data;
                $viewPath = 'h5_apps.teacher.attendance';
                break;
            default:
                $this->dataForView['items'] = [];
                $this->dataForView['typeId'] = $this->getTypeId($type);
                break;
        }

        return view($viewPath, $this->dataForView);
    }

    private function loadAttendances($schoolId, SchoolConfiguration $configuration){
        $dao = new AttendanceSchedulesDao();
        return $dao->getSpecialAttendancesForApp($schoolId, $configuration->getTermStartDate());
    }

    /**
     * 加载校历事件
     * @param $schoolId
     * @param SchoolConfiguration $configuration
     * @return array
     */
    private function loadEvents($schoolId, SchoolConfiguration $configuration){
        $dao = new CalendarDao();
        $data = $dao->getCalendarEvent($schoolId);
        $weeks = $configuration->getAllWeeksOfTerm();
        $tags = [];
        foreach ($data as $datum) {
            $week = $configuration->getScheduleWeek($datum->event_time, $weeks);
            $datum->week = $week->getName();
        }
        return [
            'events'=>$data,
            'tags'=>$tags,
        ];
    }

    private function getTypeId($txt){
        if($txt === '科技成果'){
            return News::TYPE_SCIENCE;
        }
        elseif ($txt === '校园风采'){
            return News::TYPE_CAMPUS;
        }
        elseif ($txt === '动态管理'){
            return News::TYPE_NEWS;
        }
    }

    public function view_news(Request $request){
        $news = (new NewsDao())->getById($request->get('id'));

        $this->dataForView['news'] = $news;
        $this->dataForView['api_token'] = null;

        return view('h5_apps.teacher.view_news', $this->dataForView);
    }

    public function management(Request $request){

    }
}