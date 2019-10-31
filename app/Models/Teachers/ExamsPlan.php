<?php
namespace App\Models\Teachers;

use Illuminate\Database\Eloquent\Model;

class ExamsPlan extends Model
{
    protected  $fillable=[
        'exam_id', 'institute_id', 'department_id', 'major_id', 'grade_id',
        'type', 'formalism', 'year', 'from', 'to'
    ];

    const TYPE_MIDTERM        = 1;
    const TYPE_FINAL          = 2;
    const TYPE_FOLLOW         = 3;
    const TYPE_REPAIR         = 4;
    const TYPE_CLEAR          = 5;
    const TYPE_MIDTERM_TEXT   = '期中考试';
    const TYPE_FINAL_TEXT     = '期末考试';
    const TYPE_FOLLOW_TEXT    = '随堂考试';
    const TYPE_REPAIR_TEXT    = '补考';
    const TYPE_CLEAR_TEXT     = '清考';


    const FORMALISM_WRITTEN        = 1;
    const FORMALISM_COMPUTER       = 2;
    const FORMALISM_WRITTEN_TEXT   = '笔试';
    const FORMALISM_COMPUTER_TXT   = '机试';


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
