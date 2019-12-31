<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OaAttendanceTeacher extends Model
{
    protected $fillable = [
        'wifi','mac_address',
        'morning_online_mine',
        'morning_offline_mine',
        'afternoon_online_mine',
        'afternoon_offline_mine',
        'night_online_mine',
        'night_offline_mine',
        'check_in_date',
        'status',
        'notice',
        'school_id',
        'user_id'
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
