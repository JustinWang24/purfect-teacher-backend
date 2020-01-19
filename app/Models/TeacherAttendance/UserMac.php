<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class UserMac extends Model
{
    //
    public $table = 'teacher_attendance_user_macs';
    protected $fillable = [
        'teacher_attendance_id','user_id','mac_address'
    ];
}
