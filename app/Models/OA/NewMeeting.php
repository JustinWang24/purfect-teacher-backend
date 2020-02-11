<?php

namespace App\Models\OA;


use App\Models\Schools\Room;
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


    public function room() {
        return $this->belongsTo(Room::class);
    }

}