<?php

namespace App\Models\AttendanceSchedules;

use App\Models\Course;
use App\Models\Timetable\TimetableItem;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected  $fillable = ['timetable_id', 'actual_number', 'leave_number',
                            'missing_number', 'total_number', 'course_id',
                            'year', 'term', 'grade_id', 'teacher_id', 'week'
    ];

    protected $hidden = ['updated_at'];

    public function course() {
        return $this->belongsTo(Course::class);
    }


    public function timeTable() {
        return $this->belongsTo(TimetableItem::class, 'timetable_id');
    }
}
