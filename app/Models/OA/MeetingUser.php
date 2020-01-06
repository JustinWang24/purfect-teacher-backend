<?php


namespace App\Models\OA;


use Illuminate\Database\Eloquent\Model;

class MeetingUser extends Model
{
    protected $table = 'oa_meeting_users';

    protected $fillable = [
        'school_id', 'meetid', 'user_id', 'signin_start', 'signin_end'
    ];
}
