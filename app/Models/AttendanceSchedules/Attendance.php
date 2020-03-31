<?php

namespace App\Models\AttendanceSchedules;

use App\Models\Schools\SchoolConfiguration;
use App\Models\Timetable\TimeSlot;
use App\User;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Schools\Grade;
use App\Models\Users\GradeUser;
use App\Models\Timetable\TimetableItem;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected  $fillable = [
        'timetable_id', 'actual_number', 'leave_number', 'missing_number',
        'total_number', 'course_id', 'year', 'term', 'grade_id', 'teacher_id',
        'week', 'time_slot_id','status', 'school_id',
    ];

    protected $hidden = ['updated_at'];


    const STATUS_UN_EVALUATE = 0;  // 未评价
    const STATUS_EVALUATE = 1;  // 已评价

    const TEACHER_SIGN = 1;  // 已签到
    const TEACHER_NO_SIGN = 0; // 未签到

    const TEACHER_LATE = 1; // 教师上课迟到
    const TEACHER_NO_LATE = 0; // 教师上课未迟到


    public function details() {
        return $this->hasMany(AttendancesDetail::class, 'attendance_id');
    }


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

    public function gradeUser() {
        return $this->belongsTo(GradeUser::class, 'teacher_id','user_id');
    }

    public function teacher() {
        return $this->belongsTo(User::class,'teacher_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'teacher_id','id');
    }

    public function timeSlot()
    {
      return $this->belongsTo(TimeSlot::class,'time_slot_id', 'id');
    }

    public function getTeacherSignTimeAttribute($value)
    {
        if (is_null($value)) {
            return '';
        }
        return Carbon::parse($value)->format('Y-m-d H:i');
    }


    public function getTerm() {
        $configuration = new SchoolConfiguration();
        $term = $configuration->getAllTerm();
        return $term[$this->term];
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

}
