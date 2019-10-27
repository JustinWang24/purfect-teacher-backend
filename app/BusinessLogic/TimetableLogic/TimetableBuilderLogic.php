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
    protected $term;
    protected $year;
    protected $timetableItemDao;

    public function __construct($schoolId, $gradeId, $term, $year = null)
    {
        $this->gradeId = $gradeId;
        $this->year = $year ?? Carbon::now()->year;
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
                $weekDayIndex, $this->year, $this->term, $this->gradeId
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