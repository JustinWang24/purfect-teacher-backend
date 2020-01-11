<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OaAttendanceTeachersMessage extends Model
{
    protected $fillable = [
        'user_id', 'manager_user_id', 'attendance_date', 'status', 'school_id','type','attendance_time','content', 'time_slot'
    ];

    //status-状态 0-未补卡 1-补卡中 2-已通过 3-未通过 type-online-上班1 offline-下班2


    const NOTOPT = 0;
    const NOTOPT_TXT = '未补卡';
    const DOINGOPT = 1;
    const DOINGOPT_TXT = '补卡中';
    const FINISH = 2;
    const FINISH_TXT = '已通过';
    const CANCLE = 3;
    const CANCLE_TXT = '未通过';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function manager(){
        return $this->belongsTo(User::class, 'manager_user_id', 'id');
    }

    public function member(){
        return $this->belongsTo(OaAttendanceTeachersGroupMember::class, 'user_id', 'user_id');
    }
}
