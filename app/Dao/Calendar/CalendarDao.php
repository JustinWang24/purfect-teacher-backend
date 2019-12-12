<?php


namespace App\Dao\Calendar;

use App\Models\Schools\SchoolCalendar;
use App\Models\Schools\SchoolConfiguration;
use App\Utils\Time\CalendarWeek;
use Illuminate\Support\Collection;

class CalendarDao
{

    /**
     * 新增
     * @param $data
     * @return mixed
     */
    public function createCalendarEvent($data)
    {
        return SchoolCalendar::create($data);
    }

    /**
     * 修改
     * @param $data
     * @return mixed
     */
    public function updateCalendarEvent($data)
    {
        $event = SchoolCalendar::find($data['id']);
        if($event){
            $event->tag = $data['tag'];
            $event->content = $data['content'];
            $event->event_time = $data['event_time'];
            return $event->save();
        }
    }

    /**
     * 获取校历的事件
     * @param $schoolId
     * @return mixed
     */
    public function getCalendarEvent($schoolId)
    {
        return SchoolCalendar::where('school_id', $schoolId)->select('id' ,'tag', 'content', 'event_time')->get();
    }

    /**
     * 获取事件详情
     */
    public function getEventById($id)
    {
        return SchoolCalendar::find($id);
    }

    /**
     * @param $start
     * @param $end
     * @param $schoolId
     * @return Collection
     */
    public function getBetween($start, $end, $schoolId){
        return SchoolCalendar::where('school_id', $schoolId)
            ->where('event_time','>=',$start)
            ->where('event_time','<=',$end)
            ->select('id' ,'tag', 'content', 'event_time')
            ->get();
    }

    /**
     * @param SchoolConfiguration $configuration
     * @param null $term
     * @return Collection
     */
    public function getCalendar(SchoolConfiguration $configuration, $term = null){
        $weeks = $configuration->getAllWeeksOfTerm($term);
        $that = $this;
        $weeks->each(function ($week, $key) use ($that, $configuration) {
            /**
             * @var CalendarWeek $week
             */
            $events = $that->getBetween($week->getStart(), $week->getEnd(), $configuration->getSchoolId());
            if(count($events) > 0)
                $week->setEvents($events);
        });

        return $weeks;
    }
}
