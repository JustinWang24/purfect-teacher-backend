<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'school_id','invited_by','status','name','mobile','vehicle_license',
        'reason','scheduled_at','arrived_at'
    ];

    public $casts = [
        'scheduled_at'=>'datetime',
        'arrived_at'=>'datetime',
    ];

    public function invitedBy(){
        return $this->belongsTo(User::class,'invited_by');
    }
}
