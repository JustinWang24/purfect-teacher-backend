<?php


namespace App\Models\Evaluate;


use App\Models\Schools\TeachingAndResearchGroup;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EvaluateTeacher extends Model
{

    protected $fillable = [
        'school_id', 'user_id', 'year', 'type', 'score', 'num', 'week', 'weekday_index', 'time_slot_id'
    ];

    const TYPE_LAST_TERM = 1;
    const TYPE_NEXT_TERM = 2;

    const TYPE_LAST_TERM_TEXT = '上学期';
    const TYPE_NEXT_TERM_TEXT = '下学期';


    /**
     * 获取评教学年
     * @return array
     */
    public function year() {
        $year = Carbon::now()->year;
        return [$year, $year -1, $year-2];
    }

    public function typeText() {
        $data = $this->allType();
        return $data[$this->type]??'';
    }

    /**
     * 评教学期
     * @return array
     */
    public function allType() {
        return [
            self::TYPE_LAST_TERM => self::TYPE_LAST_TERM_TEXT,
            self::TYPE_NEXT_TERM => self::TYPE_NEXT_TERM_TEXT,
        ];
    }


    public function user(){
        return $this->belongsTo(User::class);
    }


    public function teacherGroup() {
        return $this->belongsTo(TeachingAndResearchGroup::class,'group_id');
    }



}
