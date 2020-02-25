<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    const NOT_VISITED = 1;
    const VISITED = 2;

    protected $fillable = [
        'user_id','school_id','invited_by','cate_id','name','mobile','vehicle_license',
        'reason','visitors_json1','visitors_json2','status','scheduled_at','arrived_at'
    ];

    public $casts = [
        'scheduled_at'=>'datetime',
        'arrived_at'=>'datetime',
    ];

    public function invitedBy(){
        return $this->belongsTo(User::class,'invited_by');
    }
}
