<?php

namespace App\Models\OA;


use App\Models\Schools\Room;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NewMeeting extends Model
{
    protected $fillable = [
        'user_id', 'approve_userid', 'school_id', 'meet_title', 'meet_content', 'signin_status',
        'signin_start', 'signin_end', 'signout_status', 'signout_start', 'signout_end', 'meet_start',
        'meet_end', 'type', 'status', 'room_id', 'room_text'
    ];


    const TYPE_CUSTOM_ROOM = 0; // 自定义地点
    const TYPE_MEETING_ROOM = 1; // 会议室


    const STATUS_CHECK = 0;  // 审核中
    const STATUS_REFUSE = 1; // 拒绝
    const STATUS_PASS = 2;   // 通过
    const STATUS_WAIT = 2;   // 待开始
    const STATUS_UNDERWAY = 3; // 进行中
    const STATUS_FINISHED = 4; // 已结束


    public function room() {
        return $this->belongsTo(Room::class);
    }


    /**
     * 会议负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approve() {
        return $this->belongsTo(User::class, 'approve_userid');
    }


    /**
     * 参会人员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meetUsers() {
        return $this->hasMany(NewMeetingUser::class, 'meet_id');
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


    /**
     * 签到时间
     * @return string
     */
    public function getSignOutTime() {
        $signout_start = Carbon::parse($this->signout_start);
        $signout_end = Carbon::parse($this->signout_end);
        $time = $signout_start->format('H:i'). '————' .$signout_end->format('H:i');
        return $signout_start->toDateString().' '. $time;
    }


    /**
     * 附件
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files() {
        return $this->hasMany(NewMeetingFile::class, 'meet_id');
    }


    /**
     * 会议纪要
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function summaries () {
        return $this->hasMany(NewMeetingSummary::class, 'meet_id');
    }

}