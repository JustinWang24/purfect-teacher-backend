<?php

namespace App\Models\Teachers;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ConferencesUser extends Model
{


    protected $fillable = [
        'conference_id','user_id','school_id'
    ];

    protected $hidden = ['updated_at'];


    public $conferences_field = ['title', 'from', 'to', 'room_id', 'user_id'];



    const UN_SIGN_IN = 0;
    const SIGN_IN    = 1;
    const SIGN_OUT   = 2;

    const UN_SIGN_IN_TEXT = '未签到';
    const SIGN_IN_TEXT    = '已签到';
    const SIGN_OUT_TEXT   = '已签退';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conference() {

        return $this->belongsTo(Conference::class)->select($this->conferences_field);
    }


}
