<?php

namespace App\Models\Timetable;

use App\Models\Course;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlot extends Model
{
    use SoftDeletes;
    const TYPE_STUDYING = 1;        // 上课时间
    const TYPE_BREAK = 2;           // 休息时间
    const TYPE_PRACTICE = 3;        // 自习时间
    const TYPE_LUNCH_BREAK = 4;     // 午休时间
    const TYPE_EXERCISE = 5;        // 课间操时间
    const TYPE_FREE_TIME = 6;       // 自由活动
    const TYPE_TIME_POINT = 7;      // 时间点
    const TYPE_BREAKFAST = 8;       // 早餐时间
    const TYPE_WAKE_UP = 9;         // 起床时间
    const TYPE_MORNING_READ = 10;   // 晨读
    const TYPE_LUNCH = 11;          // 午餐
    const TYPE_PREPARE = 12;        // 预备
    const TYPE_DAY_END = 13;        // 就寝/熄灯
    const TYPE_DINNER = 14;         // 晚餐
    const TYPE_PRACTICE_EVENING = 15;  // 晚自习

    const TYPE_STUDYING_TXT = '上课时间';
    const TYPE_BREAK_TXT = '休息时间';
    const TYPE_PRACTICE_TXT = '自习时间';
    const TYPE_LUNCH_BREAK_TXT = '午休时间';
    const TYPE_EXERCISE_TXT = '课间操';
    const TYPE_FREE_TIME_TXT = '自由活动';
    const TYPE_TIME_POINT_TXT = '时间点';
    const TYPE_BREAKFAST_TXT = '早餐';
    const TYPE_WAKE_UP_TXT = '起床';
    const TYPE_MORNING_READ_TXT = '晨读';
    const TYPE_LUNCH_TXT = '午餐';
    const TYPE_DINNER_TXT = '晚餐';
    const TYPE_PREPARE_TXT = '预备';
    const TYPE_DAY_END_TXT = '就寝/熄灯';
    const TYPE_PRACTICE_EVENING_TXT = '晚自习';

    const SEASONS_WINTER_AND_SPRINT = 1;
    const SEASONS_WINTER_AND_SPRINT_TEXT = '冬季/春季';
    const SEASONS_SUMMER_AND_AUTUMN = 2;
    const SEASONS_SUMMER_AND_AUTUMN_TEXT = '夏季/秋季';

    protected $fillable = [
        'school_id','name','from','to','type','season'
    ];

    public static function AllTypes(){
        return [
            self::TYPE_WAKE_UP => self::TYPE_WAKE_UP_TXT,
            self::TYPE_BREAKFAST => self::TYPE_BREAKFAST_TXT,
            self::TYPE_MORNING_READ => self::TYPE_MORNING_READ_TXT,
            self::TYPE_PREPARE => self::TYPE_PREPARE_TXT,
            self::TYPE_LUNCH => self::TYPE_LUNCH_TXT,
            self::TYPE_DINNER => self::TYPE_DINNER_TXT,
            self::TYPE_PRACTICE_EVENING => self::TYPE_PRACTICE_EVENING_TXT,
            self::TYPE_DAY_END => self::TYPE_DAY_END_TXT,
            self::TYPE_STUDYING => self::TYPE_STUDYING_TXT,
            self::TYPE_BREAK => self::TYPE_BREAK_TXT,
            self::TYPE_PRACTICE => self::TYPE_PRACTICE_TXT,
            self::TYPE_LUNCH_BREAK => self::TYPE_LUNCH_BREAK_TXT,
            self::TYPE_EXERCISE => self::TYPE_EXERCISE_TXT,
            self::TYPE_FREE_TIME => self::TYPE_FREE_TIME_TXT,
            self::TYPE_TIME_POINT => self::TYPE_TIME_POINT_TXT,
        ];
    }

    public static function Seasons(){
        return [
            self::SEASONS_WINTER_AND_SPRINT=>self::SEASONS_WINTER_AND_SPRINT_TEXT,
            self::SEASONS_SUMMER_AND_AUTUMN=>self::SEASONS_SUMMER_AND_AUTUMN_TEXT,
        ];
    }

    /**
     * @return string
     */
    public function getTypeTextAttribute(){
        return self::AllTypes()[$this->type];
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function getFromAttribute($val) {
        return Carbon::parse($val)->format('H:i');
    }


    public function getToAttribute($val) {
        return Carbon::parse($val)->format('H:i');
    }
}
