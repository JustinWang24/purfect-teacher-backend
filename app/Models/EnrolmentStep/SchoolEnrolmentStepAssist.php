<?php

namespace App\Models\EnrolmentStep;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_enrolment_step_id
 * @property int $user_id
 */
class SchoolEnrolmentStepAssist extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['school_enrolment_step_id', 'user_id'];

}
