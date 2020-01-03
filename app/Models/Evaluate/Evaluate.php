<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    protected $fillable = [
        'school_id', 'title', 'score'
    ];

    public $hidden = ['updated_at'];

    const TYPE_TEACHER = 1;
    const TYPE_STUDENT = 2;

    const TYPE_TEACHER_TEXT = '对老师评价';
    const TYPE_STUDENT_TEXT = '对学生评价';

    public static function allType() {
        return [
            self::TYPE_TEACHER => self::TYPE_TEACHER_TEXT,
            self::TYPE_STUDENT => self::TYPE_STUDENT_TEXT,
        ];
    }

    public function typeText() {
        $allStatus = self::allType();
        return $allStatus[$this->type];
    }
}
