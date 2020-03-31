<?php


namespace App\Models\AttendanceSchedules;

use App\User;
use App\Models\Timetable\TimetableItem;
use Illuminate\Database\Eloquent\Model;

class AttendancesDetail extends Model
{
    protected $fillable = ['attendance_id', 'course_id', 'timetable_id',
        'student_id', 'year', 'term', 'type', 'week', 'mold', 'status',
        'reason', 'weekday_index', 'score', 'remark'
    ];

    // 签到类型
    const TYPE_INTELLIGENCE = 1; // 云班牌签到
    const TYPE_MANUAL = 2;      // 手动签到
    const TYPE_SWEEP_CODE = 3; // 扫码补签

    const  TYPE_INTELLIGENCE_TEXT = '人脸识别签到';
    const  TYPE_MANUAL_TEXT = '手动签到';
    const  TYPE_SWEEP_CODE_TEXT = '扫码补签';


    // 详情类型
    const MOLD_SIGN_IN = 1;  // 签到
    const MOLD_LEAVE   = 2;  // 请假
    const MOLD_TRUANT  = 3;  // 旷课


    const MOLD_SIGN_IN_TEXT = '已签到';
    const MOLD_LEAVE_TEXT   = '请假';
    const MOLD_TRUANT_TEXT  = '旷课';


    protected $hidden = ['updated_at'];

    public function allType()
    {
        return [
            self::TYPE_INTELLIGENCE => self::TYPE_INTELLIGENCE_TEXT,
            self::TYPE_MANUAL => self::TYPE_MANUAL_TEXT,
            self::TYPE_SWEEP_CODE => self::TYPE_SWEEP_CODE_TEXT,
        ];
    }

    public function typeText()
    {
        return $this->allType()[$this->type];
    }


    public function timetable(){
        return $this->belongsTo(TimetableItem::class, 'timetable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function allMold() {
        return [
            self::MOLD_SIGN_IN => self::MOLD_SIGN_IN_TEXT,
            self::MOLD_LEAVE => self::MOLD_LEAVE_TEXT,
            self::MOLD_TRUANT => self::MOLD_TRUANT_TEXT,
        ];
    }

    public function getMold() {
        $allMold = $this->allMold();
        return $allMold[$this->mold] ?? '';
    }
}
