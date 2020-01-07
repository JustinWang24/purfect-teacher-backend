<?php


namespace App\Models\OA;


use App\User;
use Illuminate\Database\Eloquent\Model;

class MeetingUser extends Model
{
    protected $table = 'oa_meeting_users';

    protected $fillable = [
        'school_id', 'meetid', 'user_id', 'start', 'end'
    ];

    const UN_SIGN_IN = 0;
    const SIGN_IN    = 1;
    const SIGN_IN_LATE = 2;

    const UN_SIGN_IN_TEXT = '未签到';
    const SIGN_IN_TEXT    = '按时签到';
    const SIGN_IN_LATE_TEXT = '迟到';


    const UN_SIGN_OUT   = 0;
    const SIGN_OUT      = 1;
    const SIGN_OUT_EARLY   = 2;

    const UN_SIGN_OUT_TEXT   = '未签退';
    const SIGN_OUT_TEXT      = '按时签退';
    const SIGN_OUT_EARLY_TEXT   = '早退';


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function meeting() {
        return $this->belongsTo(Meeting::class,'meetid');
    }
}
