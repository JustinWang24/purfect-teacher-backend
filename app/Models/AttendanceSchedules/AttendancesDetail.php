<?php


namespace App\Models\AttendanceSchedules;

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

    // 请假状态
    const STATUS_UNCHECKED = 0;  // 未审核
    const STATUS_CONSENT   = 1;  // 同意
    const STATUS_REFUSE    = 2;  // 拒绝


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
}
