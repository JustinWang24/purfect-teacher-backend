<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    const NOT_VISITED = 1;
    const VISITED = 2;

    protected $fillable = [
        'id','uuid','user_id','school_id','invited_by','cate_id','name','mobile','vehicle_license',
        'reason','visitors_json1','visitors_json2','share_url','qrcode_url','status','scheduled_at','arrived_at'
    ];

    public $casts = [
        'scheduled_at'=>'datetime',
        'arrived_at'=>'datetime',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function invitedBy(){
        return $this->belongsTo(User::class,'invited_by');
    }
}
