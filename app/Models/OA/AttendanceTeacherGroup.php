<?php

namespace App\Models\OA;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class AttendanceTeacherGroup extends Model
{
    //
    const UNCHECKED = 0;
    const UNCHECKED_TEXT = '未打卡';
    const CHECKED = 1;
    const CHECKED_TEXT = '正常打卡';
    const BELATE = 2;
    const BELATE_TEXT = '迟到';
    const LEAVEEARLY = 2;
    const LEAVEEARLY_TEXT = '早退';
    const SERIOUSLATE = 3;
    const SERIOUSLATE_TEXT = '严重迟到';
    const LOST = 5;
    const LOST_TEXT = '缺卡';
    const OFFWORK =4;
    const OFFWORK_TEXT = '下班打卡';
    const BTNLEAVEEARLY =5;
    const BTNLEAVEEARLY_TEXT = '早退打卡';
    const BTNFINISH =6;
    const BTNFINISH_TEXT = '已打完卡';
    const BTNNULL =0;
    const BTNNULL_TEXT = '不在打卡时间';

    protected $fillable = [
        'name', 'online_time', 'offline_time', 'late_duration', 'serious_late_duration', 'wifi_name', 'school_id'
    ];


    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function members(){
        return $this->hasMany(AttendanceTeachersGroupMember::class, 'group_id', 'id');
    }

}
