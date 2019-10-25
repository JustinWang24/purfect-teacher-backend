<?php

namespace App\Models\Teachers;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;
class Conference extends Model
{
    protected  $fillable=[
        'title','school_id','user_id','room_id','sign_out','to','from','video','remark',
    ];



    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id','id');
    }


    /**
     * 负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * 邀请人
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participant()
    {
        return $this->hasMany(ConferencesUser::class, 'conference_id', 'id');
    }
}