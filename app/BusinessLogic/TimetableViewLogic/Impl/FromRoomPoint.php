<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 7:37 PM
 */

namespace App\BusinessLogic\TimetableViewLogic\Impl;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FromRoomPoint extends AbstractPointView
{
    private $roomId;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->roomId = $request->get('room');
    }

    public function build()
    {
        $timetable = [];
        foreach (range(1, 7) as $weekDayIndex) {
            $timetable[] = $this->timetableItemDao->getItemsByWeekDayIndexForRoomView(
                $weekDayIndex, $this->year, $this->term, $this->weekType, $this->roomId
            );
        }

        // 检查从当前时刻起的特殊情况, 主要就是调课
        $today = Carbon::today();
        $specialCases = $this->timetableItemDao->getSpecialsAfterTodayForRoomView(
            $this->year, $this->term, $this->roomId, $today
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