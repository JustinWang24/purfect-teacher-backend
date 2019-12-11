<?php
/**
 * 在这里实现前端加载课程表所需要的项目的具体方法
 */

namespace App\BusinessLogic\TimetableLogic;
use App\Dao\Timetable\TimeSlotDao;
use App\Dao\Timetable\TimetableItemDao;
use Carbon\Carbon;

class TimetableBuilderLogic
{
    protected $schoolId;
    protected $gradeId;
    protected $weekType;
    protected $term;
    protected $year;
    protected $timetableItemDao;

    /**
     * TimetableBuilderLogic constructor.
     * @param $schoolId: 学校 ID
     * @param $gradeId: 年级 ID
     * @param $weekType: 单双周
     * @param $term: 学期
     * @param null $year: 学年
     */
    public function __construct($schoolId, $gradeId, $weekType, $term, $year = null)
    {
        $this->gradeId = $gradeId;
        $this->year = $year ?? Carbon::now()->year;
        $this->weekType = $weekType;
        $this->term = $term;
        $this->schoolId = $schoolId;
    }

    /**
     * 创建课程表的具体方法
     * @return mixed
     */
    public function build(){
        $timetable = [];

        // 找到所有的和学习相关的时间段
        $timeSlotDao = new TimeSlotDao();
        $forStudyingSlots = $timeSlotDao->getAllStudyTimeSlots($this->schoolId);
        // 构建课程表项的 DAO
        $this->timetableItemDao = new TimetableItemDao($forStudyingSlots);

        foreach (range(1, 7) as $weekDayIndex) {
            $timetable[] = $this->timetableItemDao->getItemsByWeekDayIndex(
                $weekDayIndex, $this->year, $this->term, $this->weekType, $this->gradeId
            );
        }

        // 检查从当前时刻起的特殊情况, 主要就是调课
        $today = Carbon::today();
        $specialCases = $this->timetableItemDao->getSpecialsAfterToday(
            $this->year, $this->term, $this->gradeId, $today
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

        return empty($timetable) ? '' : $timetable;
    }
}