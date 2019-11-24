<?php


namespace App\Dao\Calendar;

use App\Models\Schools\Calendar;

class CalendarDao
{

    public function createCalendarEvent($data)
    {
        return Calendar::create($data);
    }


    public function updateCalendarEvent()
    {

    }
}
