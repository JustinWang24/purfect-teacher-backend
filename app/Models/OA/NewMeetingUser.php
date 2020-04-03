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

    public $hidden = ['updated_at'];

    const UN_SIGNIN = 0;  // 未签到
    const NORMAL_SIGNIN = 1; // 按时签到
    const LATE = 2; // 迟到

    const UN_SIGNOUT = 0; // 未签退
    const NORMAL_SIGNOUT = 1; // 正常签退
    const LEAVE_EARLY = 2; // 早退

    const CLOSE = 2; // 关闭



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
     * 参会人员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }


    /**
     * 会议时间
     * @return string
     */
    public function getMeetTime() {
        $meet_start = Carbon::parse($this->meet_start);
        $meet_end = Carbon::parse($this->meet_end);
        $time = $meet_start->format('H:i'). '–' .$meet_end->format('H:i');
        return $meet_start->toDateString().' '. $time;
    }


    /**
     * 签到时间
     * @return string
     */
    public function getSignInTime() {
        $signin_start = Carbon::parse($this->signin_start);
        $signin_end = Carbon::parse($this->signin_end);
        $time = $signin_start->format('H:i'). '–' .$signin_end->format('H:i');
        return $signin_start->toDateString().' '. $time;
    }


    /**
     * 签到时间
     * @return string
     */
    public function getSignOutTime() {
        $signout_start = Carbon::parse($this->signout_start);
        $signout_end = Carbon::parse($this->signout_end);
        $time = $signout_start->format('H:i'). '–' .$signout_end->format('H:i');
        return $signout_start->toDateString().' '. $time;
    }



    public function getUserSignOutTime() {
        if(is_null($this->signout_time)) {
            return null;
        }
        return Carbon::parse($this->signout_time)->format('Y-m-d H:i');
    }


    public function getUserSignInTime() {
        if(is_null($this->signin_time)) {
            return null ;
        }
        return Carbon::parse($this->signin_time)->format('Y-m-d H:i');
    }
}