<?php

namespace App\Models\OA;


use App\Models\Schools\Room;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NewMeetingUser extends Model
{

    protected $fillable = [
        'meet_id', 'user_id', 'signin_status', 'signin_time', 'signout_status',
        'signout_time'
    ];

    const UN_SIGNIN = 0;  // 未签到
    const NORMAL_SIGNIN = 1; // 按时签到
    const LATE = 2; // 迟到

    const UN_SIGNOUT = 0; // 未签退
    const NORMAL_SIGNOUT = 1; // 正常签退



    /**
     * 会议负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approve() {
        return $this->belongsTo(User::class, 'approve_userid');
    }


    /**
     * 会议地点
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }


    /**
     * 会议时间
     * @return string
     */
    public function getMeetTime() {
        $meet_start = Carbon::parse($this->meet_start);
        $meet_end = Carbon::parse($this->meet_end);
        $time = $meet_start->format('H:i'). '————' .$meet_end->format('H:i');
        return $meet_start->toDateString().' '. $time;
    }


    /**
     * 签到时间
     * @return string
     */
    public function getSignInTime() {
        $signin_start = Carbon::parse($this->signin_start);
        $signin_end = Carbon::parse($this->signin_end);
        $time = $signin_start->format('H:i'). '————' .$signin_end->format('H:i');
        return $signin_start->toDateString().' '. $time;
    }
}