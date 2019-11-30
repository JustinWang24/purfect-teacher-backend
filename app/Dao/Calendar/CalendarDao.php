<?php


namespace App\Dao\Calendar;

use App\Models\Schools\SchoolCalendar;

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

}
