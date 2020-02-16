<?php

namespace App\Models\AttendanceSchedules;

use App\Models\Course;
use App\Models\Schools\Grade;
use App\Models\Timetable\TimetableItem;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected  $fillable = ['timetable_id', 'actual_number', 'leave_number',
                            'missing_number', 'total_number', 'course_id',
                            'year', 'term', 'grade_id', 'teacher_id', 'week'
    ];

    protected $hidden = ['updated_at'];


    const STATUS_UN_EVALUATE = 0;  // 未评价
    const STATUS_EVALUATE = 1;  // 已评价

    const TEACHER_SIGN = 1;
    const TEACHER_NO_SIGN = 0;

    const TEACHER_LATE = 1; // 教师上课迟到
    const TEACHER_NO_LATE = 0; // 教师上课未迟到



    public function course() {
        return $this->belongsTo(Course::class);
    }


    public function timeTable() {
        return $this->belongsTo(TimetableItem::class, 'timetable_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade() {
        return $this->belongsTo(Grade::class);
    }


    public function teacher() {
        return $this->belongsTo(User::class,'teacher_id');
    }
}
