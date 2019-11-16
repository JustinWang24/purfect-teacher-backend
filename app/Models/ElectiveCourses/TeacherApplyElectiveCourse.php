<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;
use App\Models\ElectiveCourses\TeacherApplyElectiveCoursesTimeSlot;



class TeacherApplyElectiveCourse extends Model
{
    const STATUS_WAITING_FOR_VERIFIED = 1;
    const STATUS_WAITING_FOR_VERIFIED_TEXT = '教师申请中';
    const STATUS_WAITING_FOR_REJECTED = 3;
    const STATUS_WAITING_FOR_REJECTED_TEXT = '申请退回';

    const STATUS_VERIFIED = 2;
    const STATUS_VERIFIED_TEXT = '审批通过';
    const STATUS_PUBLISHED = 4;
    const STATUS_PUBLISHED_TEXT = '已经发布到课程表';

    protected $fillable = [
        'school_id', 'teacher_id', 'teacher_name', 'major_id',
        'code', 'name', 'scores', 'year', 'term', 'desc', 'open_num','min_applied',
        'status', 'reply_content'
    ];


    public function TimeSlot(){
        return $this->hasMany(ApplyGroup::class, 'apply_id', 'id');
    }

    /**
     * @return string
     */
    public function getStatusText(){
        $text = self::STATUS_WAITING_FOR_VERIFIED_TEXT;

        switch ($this->status){
            case self::STATUS_VERIFIED:
                $text = self::STATUS_VERIFIED_TEXT;
                break;
            case self::STATUS_WAITING_FOR_REJECTED:
                $text = self::STATUS_WAITING_FOR_REJECTED_TEXT;
                break;
            case self::STATUS_PUBLISHED:
                $text = self::STATUS_PUBLISHED_TEXT;
                break;
            default:
                break;
        }
        return $text;
    }
}
