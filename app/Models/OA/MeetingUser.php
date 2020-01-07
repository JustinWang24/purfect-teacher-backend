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
    const SIGN_OUT   = 2;

    const UN_SIGN_IN_TEXT = '未签到';
    const SIGN_IN_TEXT    = '已签到';
    const SIGN_OUT_TEXT   = '已签退';

    public function user() {
        return $this->belongsTo(User::class);
    }
}
