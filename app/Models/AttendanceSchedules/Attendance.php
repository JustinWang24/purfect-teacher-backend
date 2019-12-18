<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected  $fillable = ['timetable_id', 'actual_number', 'leave_number',
                            'missing_number', 'total_number', 'course_id',
                            'year', 'term', 'grade_id', 'teacher_id', 'week'
    ];


}
