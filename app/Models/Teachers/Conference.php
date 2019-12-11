<?php

namespace App\Models\Teachers;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;
class Conference extends Model
{

    const STATUS_UNCHECK  = 0;    //未审核
    const STATUS_CHECK    = 1;    //审核
    const STATUS_REFUSE   = 2;    //拒绝
    const STATUS_WAITING  = 3;    //等待开始
    const STATUS_UNDERWAY = 4;    //进行中
    const STATUS_FINISHED = 5;    //已结束

    const STATUS_UNCHECK_TEXT = '未审核';
    const STATUS_CHECK_TEXT   = '已审核';
    const STATUS_REFUSE_TEXT  = '拒绝';

    protected  $fillable=[
        'title','school_id','user_id','room_id','sign_out','to','from','video','remark',
    ];

    protected $hidden=['updated_at'];


    public $room_field = ['name'];

    public $user_field = ['id','uuid','name','mobile'];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id','id');
    }


    /**
     * 负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {

        return $this->belongsTo(User::class)->select($this->user_field);
    }

    /**
     * 邀请人
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany(ConferencesUser::class, 'conference_id', 'id');
    }


    public function room() {

        return $this->belongsTo(User::class)->select($this->room_field);
    }


    public function medias() {

        return $this->hasMany(ConferencesMedia::class);

    }
}
