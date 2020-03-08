<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    public $table = 'teacher_attendance_organizations';
    protected $fillable = [
        'teacher_attendance_id','organization_id'
    ];
}
