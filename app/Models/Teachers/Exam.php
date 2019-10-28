<?php
namespace App\Models\Teachers;

use App\Models\Schools\Room;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected  $fillable=[
       'school_id', 'name','course_id','room_id','semester','formalism','type','year',
        'month','week','day','exam_time','from','to','status',
    ];

    const TYPE_MIDTERM   = 1;
    const TYPE_FINAL     = 2;
    const TYPE_FOLLOW    = 3;
    const TYPE_REPAIR    = 4;
    const TYPE_POSTPONE  = 5;
    const TYPE_CLEAR     = 6;
    const TYPE_MIDTERM_TEXT   = '期中考试';
    const TYPE_FINAL_TEXT     = '期末考试';
    const TYPE_FOLLOW_TEXT    = '随堂考试';
    const TYPE_REPAIR_TEXT    = '补考';
    const TYPE_POSTPONE_TEXT  = '缓考';
    const TYPE_CLEAR_TEXT     = '清考';


    /**
     * 获取考场
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rooms()
    {
        return $this->belongsTo(Room::class, 'room_id','id');
    }


    /**
     * 获取课程
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Room::class, 'course_id','id');
    }


    /**
     * 获取type属性
     * @return string
     */
    public function getTypeAttribute()
    {
        switch ($this->type)
        {
            case self::TYPE_MIDTERM:  return self::TYPE_MIDTERM_TEXT;break;
            case self::TYPE_FINAL:    return self::TYPE_FINAL_TEXT;break;
            case self::TYPE_FOLLOW:   return self::TYPE_FOLLOW_TEXT;break;
            case self::TYPE_REPAIR:   return self::TYPE_REPAIR_TEXT;break;
            case self::TYPE_POSTPONE; return self::TYPE_POSTPONE_TEXT;break;
            case self::TYPE_CLEAR;    return self::TYPE_CLEAR_TEXT;break;
            default : return '';
        }

    }

}