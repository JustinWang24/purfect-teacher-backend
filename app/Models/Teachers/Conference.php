<?php

namespace App\Models\Teachers;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;
class Conference extends Model
{

    const STATUS_UNCHECK = 0;    //未审核
    const STATUS_CHECK   = 1;    //审核
    const STATUS_REFUSE  = 2;    //拒绝

    const STATUS_UNCHECK_TEXT = '未审核';
    const STATUS_CHECK_TEXT   = '已审核';
    const STATUS_REFUSE_TEXT  = '拒绝';

    protected  $fillable=[
        'title','school_id','user_id','room_id','sign_out','date','to','from','video','remark',
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