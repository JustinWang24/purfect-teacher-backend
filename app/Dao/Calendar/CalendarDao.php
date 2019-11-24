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
        return SchoolCalendar::where('id', $data['id'])->update($data);
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
