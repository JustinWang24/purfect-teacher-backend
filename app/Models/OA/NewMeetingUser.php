<?php

namespace App\Models\OA;


use Illuminate\Database\Eloquent\Model;

class NewMeetingUser extends Model
{

    protected $fillable = [
        'meet_id', 'user_id', 'signin_status', 'signin_time', 'signout_status',
        'signout_time'
    ];
}