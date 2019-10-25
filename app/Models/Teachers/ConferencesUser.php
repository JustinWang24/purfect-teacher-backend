<?php

namespace App\Models\Teachers;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ConferencesUser extends Model
{


    protected $fillable = [
        'conference_id','user_id','school_id','status','date','from','to','begin','end',
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}