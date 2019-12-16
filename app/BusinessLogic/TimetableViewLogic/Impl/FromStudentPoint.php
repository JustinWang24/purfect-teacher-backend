<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/12/19
 * Time: 10:49 PM
 */

namespace App\BusinessLogic\TimetableViewLogic\Impl;
use App\BusinessLogic\TimetableViewLogic\Contracts\ITimetableBuilder;
use App\Dao\Timetable\TimeSlotDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Models\School;
use App\Models\Schools\SchoolConfiguration;
use App\Models\Timetable\TimeSlot;
use App\Models\Users\GradeUser;
use App\User;
use App\Utils\Time\CalendarWeek;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FromStudentPoint implements ITimetableBuilder
{
    /**
     * @var User $student
     */
    protected $student;
    /**
     * @var GradeUser $gradeUser
     */
    protected $gradeUser;
    /**
     * @var School
     */
    protected $school;
    /**
     * @var SchoolConfiguration $schoolConfiguration
     */
    protected $schoolConfiguration;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $allWeeks;
    /**
     * @var CalendarWeek $currentWeek
     */
    protected $currentWeek;
    /**
     * @var boolean $isOddWeek
     */
    protected $isOddWeek;
    protected $weekType;
    protected $term;
    protected $year;
    protected $timetableItemDao;
    protected $timeSlotDao;
    /**
     * @var TimeSlot[] $timeSlots
     */
    protected $timeSlots;

    // 需要的数据类型: 按天还是按周来存取
    protected $requestedDataType = null;
    protected $requestedWeekIndex = -1;
    protected $requestedDate = null;

    public function __construct(Request $request)
    {
        $this->student = $request->user();
        $this->gradeUser = $this->student->gradeUser;

        $this->school = $this->gradeUser->school;
        $this->schoolConfiguration = $this->school->configuration;

        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        $now = now(GradeAndYearUtil::TIMEZONE_CN);
        $startDate = $this->schoolConfiguration->getTermStartDate();
        $this->year = $startDate->year;
        $this->term = $this->schoolConfiguration->guessTerm($now->month);
        $this->allWeeks = $this->schoolConfiguration->getAllWeeksOfTerm();

        // 获取请求的数据的类型: 是指定某一天的, 还是指定某一周的
        $this->requestedDataType = $request->get('type', ITimetableBuilder::STUDENT_REQUEST_TYPE_DAILY);

        if($request->has('week')){
            // 表示需要按周返回数据
            $this->requestedWeekIndex = $request->get('week', -1);
        }

        if($request->has('day')){
            $this->requestedDate = Carbon::createFromFormat('Y-m-d',$request->get('day'));
        }
        else{
            $this->requestedDate = now(GradeAndYearUtil::TIMEZONE_CN);
        }

        $this->setUp($this->requestedDate);

        $this->weekType = $this->isOddWeek ? GradeAndYearUtil::WEEK_ODD : GradeAndYearUtil::WEEK_EVEN; // 单双周
        $this->timeSlotDao = new TimeSlotDao($this->school);
        $this->timeSlots = $this->timeSlotDao->getAllStudyTimeSlots($this->school->id);
        $this->timetableItemDao = new TimetableItemDao($this->timeSlots);
    }

    public function build()
    {
        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        if($this->requestedDataType === ITimetableBuilder::STUDENT_REQUEST_TYPE_DAILY){
            $timetable = $this->buildTimetableInDailyFormat();
        }
        else{
            $timetable = $this->buildTimetableInWeeklyFormat();
        }
        return [
            'timetable'=>$timetable,
            'timeSlots'=>$this->timeSlots,
            'current'=>[
                'date'=>$this->requestedDate->format("Y-m-d"),
                'weekday'=>$this->requestedDate->weekday(),
                'calendarWeekIndex'=>$this->currentWeek->getName(),
                'odd'=>$this->isOddWeek?'单周':'双周'
            ]
        ];
    }

    /**
     * @return array
     */
    protected function buildTimetableInDailyFormat(){
        $timetable = [];
        $timetable[] = $this->timetableItemDao->getItemsByWeekDayIndexForApp(
            $this->requestedDate->weekday(), $this->year, $this->term, $this->weekType, $this->gradeUser->grade_id
        );
        // 检查从当前时刻起的特殊情况, 主要就是调课
        $specialCases = $this->timetableItemDao->getSpecialsAfterToday(
            $this->year, $this->term, $this->gradeUser->grade_id, $this->requestedDate
        );

        $specialKeys = array_keys($specialCases);
        foreach ($timetable as $idx=>$column) {
            foreach ($column as $key=>$item) {
                if(!empty($item) && in_array($item['id'], $specialKeys)){
                    // 在 specials 放入所有调课的特殊 item 的 id 值数组
                    $timetable[$idx][$key]['specials'] = $specialCases[$item['id']];
                }
            }
        }
        return empty($timetable) ? null : $timetable;
    }

    /**
     * @return array
     */
    protected function buildTimetableInWeeklyFormat(){
        $timetable = [];
        foreach (range(1, 7) as $weekDayIndex) {
            $timetable[] = $this->timetableItemDao->getItemsByWeekDayIndex(
                $weekDayIndex, $this->year, $this->term, $this->weekType, $this->gradeUser->grade_id
            );
        }
        // 检查从当前时刻起的特殊情况, 主要就是调课
        $today = Carbon::today();
        $specialCases = $this->timetableItemDao->getSpecialsAfterToday(
            $this->year, $this->term, $this->gradeUser->grade_id, $today
        );

        $specialKeys = array_keys($specialCases);
        foreach ($timetable as $idx=>$column) {
            foreach ($column as $key=>$item) {
                if(!empty($item) && in_array($item['id'], $specialKeys)){
                    // 在 specials 放入所有调课的特殊 item 的 id 值数组
                    $timetable[$idx][$key]['specials'] = $specialCases[$item['id']];
                }
            }
        }
        return empty($timetable) ? null : $timetable;
    }

    /**
     * 给定日期根据校历, 是单周吗? 注意, 校历的第一周是预备周
     * @param Carbon $date
     */
    protected function setUp(Carbon $date){
        $this->isOddWeek = false;
        foreach ($this->allWeeks as $key => $week) {
            /**
             * @var CalendarWeek $week
             */
            if($week->includes($date)){
                $this->currentWeek = $week;
                $this->isOddWeek = $key % 2 !== 0;
                $this->requestedWeekIndex = $key;
                break;
            }
        }
    }
}