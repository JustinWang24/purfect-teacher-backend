<?php

namespace App\Models;

use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Models\Courses\CourseArrangement;
use App\Models\Courses\CourseTextbook;
use App\Models\ElectiveCourses\CourseElective;
use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
use App\Models\Schools\SchoolConfiguration;
use App\Models\Timetable\TimetableItem;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\Time\CalendarWeek;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Courses\CourseTeacher;
use App\Models\Courses\CourseMajor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\User;
use PHPUnit\Framework\Constraint\Count;

class Course extends Model
{
    const OBLIGATORY_COURSE = 0; //必修课
    const ELECTIVE_COURSE   = 1; //选修课
    const FIRST_TERM        = 1; //第一学期
    const SECOND_TERM       = 2; //第二学期

    use SoftDeletes;
    protected $fillable = [
        'code','name','uuid',
        'scores',
        'optional',
        'year',
        'term',
        'desc','school_id'
    ];

    public $hidden = ['deleted_at'];

    public $casts = [
        'optional' => 'boolean', // 是否选修课
    ];

    public function majors(){
        return $this->hasMany(CourseMajor::class)->select(DB::raw('major_id as id, major_name as name'));
    }

    public function teachers(){
        return $this->hasMany(CourseTeacher::class)->select(DB::raw('teacher_id as id, teacher_name as name'));
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    /**
     * 课程关联的课程表项目
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timetableItems(){
        return $this->hasMany(TimetableItem::class);
    }

    public function courseArrangements()
    {
        return $this->hasMany(CourseArrangement::class);
    }

    public function courseElective()
    {
        return $this->hasOne(CourseElective::class);
    }


    /**
     * 本课程的课程安排
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function arrangements(){
        return $this->hasMany(CourseArrangement::class)
            ->orderBy('week', 'asc')
            ->orderBy('day_index','asc');
    }



    /**
     * 课程和教材关联
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseTextbooks() {
        return $this->hasMany(CourseTextbook::class);
    }


    /**
     * todo 该方法暂定 后续再完善 求旷课数据
     * 获取当前课程的全部时间
     * @param User $user
     * @param null $term
     * @param null $year
     * @return array
     */
    public function getSchedule(User $user, $term = null, $year = null){
        /**
         * @var SchoolConfiguration $config
         */
        $config = (new SchoolDao())->getSchoolById($user->getSchoolId())->configuration;

        $items = TimetableItem::where('year',$year??date('Y'))
            ->where('term',$term??$config->guessTerm(now(GradeAndYearUtil::TIMEZONE_CN)->month))
            ->where('course_id',$this->id)
            ->where('grade_id', $user->gradeUser->grade_id)
            ->get();

        $weeks = $config->getAllWeeksOfTerm($term,false);
        $dates = [];
        foreach ($weeks as $key =>$week) {
            $weekIndex = $week->getScheduleWeekIndex();
            foreach ($items as $k=>$item) {

                // 正常课程
                if(empty($item->at_special_datetime) && empty($item->to_special_datetime)) {

                    $result = $this->returnRepeatUnitData($week, $item, $weekIndex);
                    if(!empty($result)) {
                        $dates[$weekIndex][] = $result;
                    }

                }
                // 增加的调课
                else {

//                    dd($item->toArray());
                }


            }
        }

        return $dates;

    }


    /**
     * @param CalendarWeek $week
     * @param TimetableItem $item
     * @return mixed
     */
    protected function returnWeekData($week, $item) {
        $weekIndex = $week->getScheduleWeekIndex();
        $dates = [
            'date'=>$week->getStart()->addDays($item->weekday_index)->format('Y-m-d'),
            'week_index'=>$weekIndex,
            'weekday_index'=>$item->weekday_index,
            'time_slot' =>$item->timeSlot->name,
            'repeat_unit'=>$item->repeat_unit
        ];

        return $dates;
    }


    /**
     * 判断单双周返回数据
     * @param CalendarWeek $week
     * @param TimetableItem $item
     * @param $weekIndex
     * @return array|mixed
     */
    protected function returnRepeatUnitData($week, $item, $weekIndex) {
        $data = [];
        switch ($item->repeat_unit) {
            case GradeAndYearUtil::TYPE_EVERY_WEEK:  // 每周都有课
                $data = $this->returnWeekData($week, $item); break;
            case GradeAndYearUtil::TYPE_EVERY_ODD_WEEK:  // 表示每单周都有课
                if ($weekIndex %2 !== 0) {
                    $data = $this->returnWeekData($week, $item);
                }
                break;
            case GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK:  // 表示每双周都有课
                if ($weekIndex %2 == 0) {
                    $data = $this->returnWeekData($week, $item);
                }
                break;
        }

        return $data;

    }

}
