<?php

namespace App\Models\Teachers;

use App\Models\School;
use App\Models\Schools\Room;
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
    const STATUS_CHECK_TEXT   = '已通过';
    const STATUS_REFUSE_TEXT  = '已拒绝';


    const TYPE_ADMINISTRATIVE = 1;
    const TYPE_STAFF          = 2;
    const TYPE_INTERIOR       = 3;

    const TYPE_ADMINISTRATIVE_TEXT = '行政会议';
    const TYPE_STAFF_TEXT          = '职工会议';
    const TYPE_INTERIOR_TEXT       = '部门内部会议';

    protected  $fillable=[
        'title', 'school_id', 'user_id', 'room_id', 'to', 'from', 'remark', 'status', 'type'
    ];

    protected $hidden=['updated_at'];


    public function allType() {
        return [
            self::TYPE_ADMINISTRATIVE => self::TYPE_ADMINISTRATIVE_TEXT,
            self::TYPE_STAFF    => self::TYPE_STAFF_TEXT,
            self::TYPE_INTERIOR => self::TYPE_INTERIOR_TEXT,
        ];
    }

    public function typeText() {
        $data = $this->allType();
        return $data[$this->type] ?? '';
    }

    public function allCheckStatus() {
        return [
            self::STATUS_UNCHECK => self::STATUS_UNCHECK_TEXT,
            self::STATUS_CHECK   => self::STATUS_CHECK_TEXT,
            self::STATUS_REFUSE  => self::STATUS_REFUSE_TEXT,
        ];
    }

    public function checkStatus() {
        $data = $this->allCheckStatus();
        return $data[$this->status] ?? '';
    }

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

        return $this->belongsTo(Room::class)->select($this->room_field);
    }


    public function medias() {

        return $this->hasMany(ConferencesMedia::class);

    }
}
