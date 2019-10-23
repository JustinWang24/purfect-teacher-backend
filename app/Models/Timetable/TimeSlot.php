<?php

namespace App\Models\Timetable;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlot extends Model
{
    use SoftDeletes;
    const TYPE_STUDYING = 1; // 上课时间
    const TYPE_BREAK = 2;    // 休息时间
    const TYPE_PRACTICE = 3; // 自习时间
    const TYPE_LUNCH_BREAK = 4; // 午休时间
    const TYPE_EXERCISE = 5;    // 课间操时间
    const TYPE_FREE_TIME = 6;    // 自由活动时间
    const TYPE_TIME_POINT = 7;    // 时间点

    const TYPE_STUDYING_TXT = '上课时间';
    const TYPE_BREAK_TXT = '休息时间';
    const TYPE_PRACTICE_TXT = '自习时间';
    const TYPE_LUNCH_BREAK_TXT = '午休时间';
    const TYPE_EXERCISE_TXT = '课间操时间';
    const TYPE_FREE_TIME_TXT = '自由活动时间';
    const TYPE_TIME_POINT_TXT = '时间点';

    protected $fillable = [
        'school_id','name','from','to','type'
    ];

    public static function AllTypes(){
        return [
            self::TYPE_STUDYING => self::TYPE_STUDYING_TXT,
            self::TYPE_BREAK => self::TYPE_BREAK_TXT,
            self::TYPE_PRACTICE => self::TYPE_PRACTICE_TXT,
            self::TYPE_LUNCH_BREAK => self::TYPE_LUNCH_BREAK_TXT,
            self::TYPE_EXERCISE => self::TYPE_EXERCISE_TXT,
            self::TYPE_FREE_TIME => self::TYPE_FREE_TIME_TXT,
            self::TYPE_TIME_POINT => self::TYPE_TIME_POINT_TXT,
        ];
    }

    public function school(){
        return $this->belongsTo(School::class);
    }
}
