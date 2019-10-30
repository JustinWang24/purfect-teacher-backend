<?php
namespace App\Models\Teachers;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected  $fillable=[
       'school_id', 'name', 'course_id', 'semester', 'formalism', 'type',
        'year', 'month', 'week', 'day', 'exam_time', 'from', 'to', 'status'
    ];

    const TYPE_MIDTERM        = 1;
    const TYPE_FINAL          = 2;
    const TYPE_FOLLOW         = 3;
    const TYPE_REPAIR         = 4;
    const TYPE_POSTPONE       = 5;
    const TYPE_CLEAR          = 6;
    const TYPE_MIDTERM_TEXT   = '期中考试';
    const TYPE_FINAL_TEXT     = '期末考试';
    const TYPE_FOLLOW_TEXT    = '随堂考试';
    const TYPE_REPAIR_TEXT    = '补考';
    const TYPE_POSTPONE_TEXT  = '缓考';
    const TYPE_CLEAR_TEXT     = '清考';


    const FORMALISM_WRITTEN        = 1;
    const FORMALISM_COMPUTER       = 2;
    const FORMALISM_WRITTEN_TEXT   = '笔试';
    const FORMALISM_COMPUTER_TXT   = '机试';


    /**
     * 获取考场
     */
    public function examsRoom()
    {
        return $this->hasMany(ExamsRoom::class, 'exam_id', 'id');
    }

    /**
     * 获取课程
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id','id');
    }


    /**
     * 获取type属性
     * @return string
     */
    public function getTypeTextAttribute()
    {
        switch ("{$this->type}")
        {
            case self::TYPE_MIDTERM:
                return self::TYPE_MIDTERM_TEXT;
                break;
            case self::TYPE_FINAL:
                return self::TYPE_FINAL_TEXT;
                break;
            case self::TYPE_FOLLOW:
                return self::TYPE_FOLLOW_TEXT;
                break;
            case self::TYPE_REPAIR:
                return self::TYPE_REPAIR_TEXT;
                break;
            case self::TYPE_POSTPONE;
                return self::TYPE_POSTPONE_TEXT;
                break;
            case self::TYPE_CLEAR;
                return self::TYPE_CLEAR_TEXT;
                break;
            default :
                return '';
        }

    }


    /**
     * 获取formalism属性
     * @return string
     */
    public function getFormalismTextAttribute()
    {
        switch("{$this->formalism}")
        {
            case self::FORMALISM_WRITTEN:
                return self::FORMALISM_WRITTEN_TEXT;
                break;
            case self::FORMALISM_COMPUTER:
                return self::FORMALISM_COMPUTER_TXT;
                break;
            default :
                return '';
        }
    }

}
