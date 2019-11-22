<?php

namespace App\Models\EnrolmentStep;

use App\Models\Schools\Campus;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $enrolment_step_id
 * @property int $school_id
 * @property int $campus_id
 * @property string $describe
 * @property integer $sort
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolEnrolmentStep extends Model
{
    const STEP_INFO   = 0;   // 查看步骤详情
    const STEP_BEFORE = 1;   // 查看步骤上一步
    const STEP_AFTER  = 2;   // 查看步骤下一步
    /**
     * @var array
     */
    protected $fillable = ['name', 'enrolment_step_id', 'school_id', 'campus_id', 'describe', 'sort', 'user_id'];

    protected $hidden = ['updated_at'];

    public $user_field = ['name', 'mobile'];

    public $campus_field = ['name'];

    public $tasks_field = ['name', 'describe', 'type'];


    /**
     * 获取步骤类型
     * @return array
     */
    public static function getStepType(){
        return [self::STEP_INFO, self::STEP_AFTER, self::STEP_BEFORE];
    }



    /**
     * 协助人
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assists() {
         return $this->hasMany(SchoolEnrolmentStepAssist::class)->select('user_id');
    }


    /**
     * 媒体文件
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medias() {
        return $this->hasMany(SchoolEnrolmentStepMedia::class);
    }


    /**
     * 迎新步骤子类
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks() {
        return $this->hasMany(SchoolEnrolmentStepTask::class)->select($this->tasks_field);
    }


    /**
     * 负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class)->select($this->user_field);
    }


    public function campus() {
        return $this->belongsTo(Campus::class)->select($this->campus_field);
    }

}
