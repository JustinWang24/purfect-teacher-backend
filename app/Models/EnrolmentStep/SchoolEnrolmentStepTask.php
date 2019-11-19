<?php

namespace App\Models\EnrolmentStep;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_enrolment_step_id
 * @property string $name
 * @property string $describe
 * @property boolean $type
 */
class SchoolEnrolmentStepTask extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['school_enrolment_step_id', 'name', 'describe', 'type'];

}
