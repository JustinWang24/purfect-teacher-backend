<?php

namespace App\Models\ElectiveCourses;

use App\Models\Course;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'code', 'name', 'scores', 'year', 'term', 'desc', 'open_num','max_num',
        'status', 'reply_content', 'start_year', 'apply_content', 'course_id'
    ];

    /**
     * 关联的老师
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * 关联的课程集合, 即选修课可以关联的课程
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function openToMajors(){
        return $this->hasMany(ApplyCourseMajor::class, 'apply_id');
    }

    /**
     * 申请所关联的上课具体的时间和地点的集合
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function arrangements(){
        return $this->hasMany(ApplyCourseArrangement::class,'apply_id');
    }

    public function TimeSlot(){
        // Todo: **** 删除 ApplyGroup 的关联关系
        return $this->hasMany(ApplyGroup::class, 'apply_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    /**
     * 获取文字的颜色
     * @return string
     */
    public function getStatusColor(){
        $text = 'text-warning';
        switch ($this->status){
            case self::STATUS_VERIFIED:
                $text = 'text-success';
                break;
            case self::STATUS_WAITING_FOR_REJECTED:
                $text = 'text-danger';
                break;
            case self::STATUS_PUBLISHED:
                $text = 'text-success';
                break;
            default:
                break;
        }
        return $text;
    }

    /**
     * 获取状态的文本
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

    /**
     * 是否申请已经获得通过
     * @return bool
     */
    public function isVerified(){
        return $this->status === self::STATUS_VERIFIED;
    }
}
