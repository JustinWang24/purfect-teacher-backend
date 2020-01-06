<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OaAttendanceTeacherCourses extends Model
{
    protected $fillable = [
        'wifi',
        'mac_address',
        'online_mine',
        'offline_mine',
        'timetable_items_id',
        'course_id',
        'check_in_date',
        'status',
        'notice',
        'school_id',
        'grade_id',
        'user_id',
        'plan_user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function member()
    {
        return $this->belongsTo(OaAttendanceTeachersGroupMember::class, 'user_id', 'user_id');
    }
}
