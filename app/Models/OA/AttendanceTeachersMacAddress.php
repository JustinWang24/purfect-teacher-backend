<?php

namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class AttendanceTeachersMacAddress extends Model
{
    protected $fillable = [
        'user_id', 'manager_user_id', 'mac_address', 'status', 'school_id','content'
    ];
}
