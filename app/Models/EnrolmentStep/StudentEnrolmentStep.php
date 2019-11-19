<?php

namespace App\Models\EnrolmentStep;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $school_enrolment_step_id
 * @property int $school_enrolment_step_task_id
 */
class StudentEnrolmentStep extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'school_enrolment_step_id', 'school_enrolment_step_task_id'];

}
