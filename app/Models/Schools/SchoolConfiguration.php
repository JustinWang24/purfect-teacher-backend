<?php

namespace App\Models\Schools;

use Carbon\Carbon;
use App\Utils\Time\CalendarWeek;
use Illuminate\Support\Collection;
use App\Utils\Time\GradeAndYearUtil;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Database\Eloquent\Model;

class SchoolConfiguration extends Model
{
    use HasConfigurations;
    const FAKE_YEAR = 1980;
    const FIRST_TERM_START_MONTH = 8; // 第一学期开学日期在 8 月份
    const SECOND_TERM_START_MONTH = 2; // 第二学期开学日期在 2 月份
    const LAST_TERM = 1;
    const NEXT_TERM = 2;


    protected $fillable = [
        ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM,
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION,
        ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR,
        'school_id',
        'apply_elective_course_from_1',
        'apply_elective_course_to_1',
        'apply_elective_course_from_2',
        'apply_elective_course_to_2',
        'first_day_term_1',
        'first_day_term_2',
        'summer_start_date',
        'winter_start_date',
        'campus_intro',
        'recruitment_intro',
        'open_for_uploading_qualification',
    ];

    public $casts = [
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION => 'boolean',
        'open_for_uploading_qualification'=>'boolean',
        'apply_elective_course_from_1'=>'datetime',
        'apply_elective_course_to_1'=>'datetime',
        'apply_elective_course_from_2'=>'datetime',
        'apply_elective_course_to_2'=>'datetime',
        'first_day_term_1'=>'datetime',
        'first_day_term_2'=>'datetime',
        'summer_start_date'=>'datetime',
        'winter_start_date'=>'datetime',
    ];

    /**
     * 为学校创建默认的配置项
     * @param $school
     * @return SchoolConfiguration
     */
    public function createDefaultConfig($school){
        $data = [
            ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM => 20,
            ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION => false,
            ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR=>1,
            'school_id'=>$school->id ?? $school
        ];
        return self::create($data);
    }

    /**
     * 获取指定的学期的当年的起始日期
     * @param int $term
     * @param date $date 2020-01-01
     * @return Carbon
     */
    public function getTermStartDate($term = null, $date = null ){
        // 获取当前时间的学年
        $date = $this->getSchoolYear($date, true);

        $now = Carbon::parse($date) ?? now() ;
        if(!$term){
            $term = $this->guessTerm($now->month);
        }
        $fieldName = 'first_day_term_'.$term;

        if($term == self::LAST_TERM) {
            return $this->$fieldName->setYear($now->year);
        } elseif ($term == self::NEXT_TERM) {
            return $this->$fieldName->setYear($now->addYear()->year);
        }
    }

    /**
     * 返回当前学年
     * @param date $date 2020-01-01
     * @param bool $format
     * @return int
     */
    public function getSchoolYear($date = null , $format = false) {
        if(is_null($date)) {
            $date = Carbon::now()->toDateString();
        }
        $time = $this->first_day_term_1->format('m-d'); // 第一学期的开始日期
        $year = Carbon::parse($date)->year;
        $nextSchoolYear = $year.'-'.$time;
        // 下一学年开学日期大于当前时间
        if($date < $nextSchoolYear  ) {
            $year = $year - 1;
            $date = $year . '-' . $time;
        }

        if($format) {
            return $date;
        }
        return $year;

    }

    /**
     * 根据指定的月份猜测在第几个学期
     * @param $month
     * @return int
     */
    public function guessTerm($month){
        return ($month >= self::FIRST_TERM_START_MONTH || $month < self::SECOND_TERM_START_MONTH) ? 1 : 2;
    }

    /**
     * @param null $term
     * @param bool $isReservesWeek
     * @param null $year
     * @return Collection
     */
    public function getAllWeeksOfTerm($term = null, $isReservesWeek = true, $year = null){
        $termStartDate = $this->getTermStartDate($term, $year);
        $fieldOfWeeksPerTerm = ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM;
        $weeksNumber = $this->$fieldOfWeeksPerTerm;

        // 预备周, 是开始日期的前一周
        $weeks = new Collection();

        // 是否需要预备周
        if($isReservesWeek) {
            $weeks->push(
            new CalendarWeek(
                '预备周',
                $termStartDate->subWeek()->format('Y-m-d'),
                $termStartDate->addDays(6)->format('Y-m-d')
                )
            );
        }


        // 工作周
        for ($i = 0; $i < $weeksNumber; $i++){
            $weeks->push(
                new CalendarWeek('第' . ($i+1) . '周',
                    $termStartDate->addDay()->format('Y-m-d'),
                    $termStartDate->addDays(6)->format('Y-m-d')
                )
            );
        }
        return $weeks;
    }

    /**
     * 获取指定的起始日期. 注意, 返回的 Carbon 对象, 只能使用月份和日期, 不能使用年
     * @param int $term
     * @return Carbon
     */
    public function getElectiveCourseAvailableFrom($term){
        $fieldName = 'apply_elective_course_from_'.$term;
        return $this->$fieldName;
    }

    /**
     * 获取指定的结束日期. 注意, 返回的 Carbon 对象, 只能使用月份和日期, 不能使用年
     * @param int $term
     * @return Carbon
     */
    public function getElectiveCourseAvailableTo($term){
        $fieldName = 'apply_elective_course_to_'.$term;
        return $this->$fieldName;
    }

    /**
     * 创建一个通用的选修课可以被选择的有效日期
     * @param $ec
     * @param $type
     * @return Carbon
     */
    public static function CreateMockEcDate($ec,$type){
        return Carbon::createFromFormat('Y-m-d',self::FAKE_YEAR.'-'.$ec[$type]['month'].'-'.$ec[$type]['day']);
    }

    public static function CreateMockDate($month,$day){
        return Carbon::createFromFormat('Y-m-d',self::FAKE_YEAR.'-'.$month.'-'.$day);
    }

    /**
     * 获取学校 ID
     * @return int
     */
    public function getSchoolId(){
        return $this->school_id;
    }

    /**
     * @param $date
     * @param $weeks
     * @param $term
     * @return CalendarWeek|null
     */
    public function getScheduleWeek($date, $weeks = null, $term = self::LAST_TERM){
        $w = null;
        if(!$weeks){
            $weeks = $this->getAllWeeksOfTerm($term);
        }

        foreach ($weeks as $key => $week) {
            /**
             * @var CalendarWeek $week
             */
            if($week->includes($date)){
                $this->isOddWeek = $key % 2 !== 0;
                $w = $week;
                break;
            }
        }
        return $w;

    }


    /**
     * 获取单双周
     * @param $date
     * @param $weeks
     * @param $term
     * @return mixed
     */
    public function getOddWeek($date, $weeks, $term) {
        $this->getScheduleWeek($date, $weeks, $term);
        return $this->isOddWeek ? GradeAndYearUtil::WEEK_EVEN : GradeAndYearUtil::WEEK_ODD;
    }


    /**
     * 返回自然年的所有周
     * @param null $year
     * @return Collection
     */
    public function getAllWeeksOfYear($year = null){
        if(is_null($year)) {
            $year = Carbon::now()->year;
        }
        // 预备周, 是开始日期的前一周
        $weeks = new Collection();

        $startDate = Carbon::parse($year.'-01-01');

        // 工作周
        for ($i = 1; $i <= 52; $i++){
            if($i == 1) {
                $weeks->push(
                    new CalendarWeek('第' . $i . '周',
                        $startDate->toDateString(),
                        $startDate->endOfWeek()->toDateString())

                    );
            }
            else {

                $startDate = $startDate->addWeek();
                $weeks->push(
                    new CalendarWeek('第' . $i . '周',
                        $startDate->startOfWeek()->toDateString(),
                        $startDate->endOfWeek()->toDateString()
                    )
                );
            }
        }
        return $weeks;
    }


}
