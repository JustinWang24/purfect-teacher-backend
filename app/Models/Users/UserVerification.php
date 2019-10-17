<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    protected $fillable = [
        'user_id','code','created_at','purpose','mobile'
    ];
    public $timestamps = false;
}
