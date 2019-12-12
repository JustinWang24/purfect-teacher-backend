<?php

namespace App\Models\Schools;

use App\Utils\Time\CalendarWeek;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Collection;

class SchoolConfiguration extends Model
{
    use HasConfigurations;
    const FAKE_YEAR = 1980;

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
    ];

    public $casts = [
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION => 'boolean',
        'apply_elective_course_from_1'=>'datetime',
        'apply_elective_course_to_1'=>'datetime',
        'apply_elective_course_from_2'=>'datetime',
        'apply_elective_course_to_2'=>'datetime',
        'first_day_term_1'=>'datetime',
        'first_day_term_2'=>'datetime',
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
     * @return Carbon
     */
    public function getTermStartDate($term = null){
        $now = now();
        if(!$term){
            $term = $now->month > 6 ? 2 : 1;
        }
        $fieldName = 'first_day_term_'.$term;
        return $this->$fieldName->setYear($now->year);
    }

    /**
     * @param null $term
     * @return Collection
     */
    public function getAllWeeksOfTerm($term = null){
        $termStartDate = $this->getTermStartDate($term);
        $fieldOfWeeksPerTerm = ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM;
        $weeksNumber = $this->$fieldOfWeeksPerTerm;

        // 预备周, 是开始日期的前一周
        $weeks = new Collection();

        $weeks->push(
            new CalendarWeek(
                '预备周',
                $termStartDate->subWeek()->format('Y-m-d'),
                $termStartDate->addDays(6)->format('Y-m-d')
            )
        );

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

    /**
     * 获取学校 ID
     * @return int
     */
    public function getSchoolId(){
        return $this->school_id;
    }
}
