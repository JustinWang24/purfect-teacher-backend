<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendancePerson extends Model
{
    protected $fillable = [
        'user_id', 'type', 'school_id', 'campus_id',
    ];
}
