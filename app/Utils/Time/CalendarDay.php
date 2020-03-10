<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 12/12/19
 * Time: 11:52 AM
 */

namespace App\Utils\Time;


use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CalendarDay implements Arrayable
{
    private $day;
    private $weekIdx;
    private $events;

    /**
     * CalendarDay constructor.
     * @param Carbon $day
     * @param CalendarWeek[]
     */
    public function __construct(Carbon $day, $weeks)
    {
        $this->day = $day;
        $this->events = new Collection();
        foreach ($weeks as $week) {
            /**
             * @var CalendarWeek $week
             */
            if($week->includes($this->day)){
                $this->weekIdx = $week->getName();
                break;
            }
        }
    }

    /**
     * @return Carbon
     */
    public function getDay(): Carbon
    {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getWeekIdx(): string
    {
        return $this->weekIdx;
    }

    /**
     * @return Collection
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * 添加一个 event
     *
     * @param $event
     * @return $this
     */
    public function addEvent($event){
        $this->events->push($event);
        return $this;
    }

    public function toArray()
    {
        $events = [];

        foreach ($this->events as $event) {
            $event->name = $this->day->format('Y-m-d');
            $event->week_idx = $this->weekIdx;
            $events[] = $event;
        }

        return [
            'name'=>$this->day->format('m-d'),
            'week_idx' => $this->weekIdx,
            'events'=>$events
        ];
    }

    /**
     * 获取给定天所在的周的第一天: 周一
     * @param Carbon $day
     * @return Carbon
     */
    public static function GetFirstDayOfTheWeek(Carbon $day){
        $idx = $day->dayOfWeek;

        if($idx === 1){
            // 表示传入的 day 就是周一, 直接返回即可
            return $day;
        }elseif($idx === 0){
            // 传入的 day 是周日, 那么第一天是往回数 6 天
            return $day->subDays(6);
        }else{
            return $day->subDays($idx - 1);
        }
    }

    /**
     * 获取给定天所在的周的最后一天, 周日
     * @param Carbon $day
     * @return Carbon
     */
    public static function GetLastDayOfTheWeek(Carbon $day){
        $idx = $day->dayOfWeek;

        if($idx === 0){
            // 传入的 day 是周日, 直接返回
            return $day;
        }else{
            return $day->addDays(7 - $idx);
        }
    }


    /**
     * 获取星期几
     * @param $day
     * @return mixed|string
     */
    public static function GetWeekDayIndex($day) {
        $data = [
            '星期日','星期一','星期二','星期三','星期四','星期五','星期六','星期日'
        ];
        return $data[$day]??'';
    }
}