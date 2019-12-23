<?php

namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class AttendanceTeachersGroupMember extends Model
{
    protected $fillable = [
        'group_id', 'user_id', 'status', 'school_id', 'mac_address'
    ];

    public function group()
    {
        return $this->hasOne(AttendanceTeacherGroup::class, 'id', 'group_id');
    }
}
