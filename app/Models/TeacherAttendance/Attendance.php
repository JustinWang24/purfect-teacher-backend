<?php

namespace App\Models\TeacherAttendance;

use App\Models\Schools\Organization;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    const AFTERNOON_USED = 1;//启用中午打卡
    const AFTERNOON_UNUSED = 0;//不启用中午打卡
    //
    public $table = 'teacher_attendances';
    protected $fillable = [
        'school_id','title','wifi_name','using_afternoon'
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'teacher_attendance_organizations', 'teacher_attendance_id', 'organization_id');
    }
    public function managers()
    {
        return $this->hasMany(Managers::class, 'teacher_attendance_id');
    }

    public function clocksets()
    {
        return $this->hasMany(Clockset::class, 'teacher_attendance_id');
    }

    public function clockins()
    {
        return $this->hasMany(Clockin::class, 'teacher_attendance_id');
    }

    public function usermacs()
    {
        return $this->hasMany(UserMac::class, 'teacher_attendance_id');
    }

    public function exceptiondays()
    {
        return $this->hasMany(ExceptionDay::class, 'teacher_attendance_id');
    }
}
