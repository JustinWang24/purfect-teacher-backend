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

    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['school_enrolment_step_id', 'name', 'describe', 'type'];

    const TYPE_OPTIONAL = 0; // 可选
    const TYPE_REQUIRED = 1; // 必选

}
