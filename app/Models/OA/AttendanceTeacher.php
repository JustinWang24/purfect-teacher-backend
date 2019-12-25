<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AttendanceTeacher extends Model
{
    protected $fillable = [
        'wifi','mac_address','online_mine','offline_mine','check_in_date', 'status', 'notice', 'school_id', 'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function member()
    {
        return $this->belongsTo(AttendanceTeachersGroupMember::class, 'user_id', 'user_id');
    }
}
