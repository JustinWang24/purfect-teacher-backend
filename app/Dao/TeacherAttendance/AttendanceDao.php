<?php

namespace App\Dao\TeacherAttendance;

use App\Models\TeacherAttendance\Attendance;

class AttendanceDao
{
    public function getByOrganizationIdArr($organizationIdArr, $school_id)
    {
        return Attendance::whereHas('organizations', function ($query) use ($organizationIdArr) {
            $query->whereIn('organizations.id', $organizationIdArr);
        })->where('school_id', $school_id)->first();
    }

    public function getOnedayClockset(Attendance $attendance, $enday)
    {
        return $attendance->clocksets()->where('week', $enday)->first();
    }

    public function getMacAddress(Attendance $attendance, $user_id)
    {
        return $attendance->usermacs()->where('user_id', $user_id)->first();
    }

    public function checkIsexceptionDayByDay(Attendance $attendance, $day)
    {
        return $attendance->exceptiondays()->where('day', $day)->exists();
    }

    public function getOnedayClockin(Attendance $attendance, $day, $user_id)
    {
        return $attendance->clockins()->where([
            ['user_id', '=', $user_id],
            ['day', '=', $day]
        ])->get();
    }


}
