<?php


namespace App\Models\OA;


use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $table = 'oa_meetings';

    protected $fillable = [
        'user_id', 'school_id', 'meet_title', 'meet_content', 'meet_address', 'signin_start',
        'signin_end', 'meet_start', 'meet_end', 'signout_status', 'signout_start', 'approve_userid'
    ];

    const NOT_SIGN_OUT = 0; // 不需要签退
    const SIGN_OUT = 1; // 需要签退

}
