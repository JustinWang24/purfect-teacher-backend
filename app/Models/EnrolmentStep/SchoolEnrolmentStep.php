<?php

namespace App\Models\EnrolmentStep;

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
    /**
     * @var array
     */
    protected $fillable = ['name', 'enrolment_step_id', 'school_id', 'campus_id', 'describe', 'sort', 'user_id'];

    protected $hidden = ['updated_at'];

    public $user_field = ['id', 'uuid', 'name', 'mobile'];

    /**
     * 协助人
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assists() {
         return $this->hasMany(SchoolEnrolmentStepAssist::class);
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
        return $this->hasMany(SchoolEnrolmentStepTask::class);
    }


    /**
     * 负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class)->select($this->user_field);
    }

}
