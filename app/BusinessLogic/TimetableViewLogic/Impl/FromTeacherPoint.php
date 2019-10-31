<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 6:43 PM
 */

namespace App\BusinessLogic\TimetableViewLogic\Impl;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FromTeacherPoint extends AbstractPointView
{
    protected $teacherId;

    /**
     * 查询的必要提交是班级 id, 年和学期
     * TimetableBuilderLogic constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->teacherId = $request->get('teacher');
    }

    public function build()
    {
        $timetable = [];
        foreach (range(1, 7) as $weekDayIndex) {
            $timetable[] = $this->timetableItemDao->getItemsByWeekDayIndexForTeacherView(
                $weekDayIndex, $this->year, $this->term, $this->weekType, $this->teacherId
            );
        }

        // 检查从当前时刻起的特殊情况, 主要就是调课
        $today = Carbon::today();
        $specialCases = $this->timetableItemDao->getSpecialsAfterTodayForTeacherView(
            $this->year, $this->term, $this->teacherId, $today
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