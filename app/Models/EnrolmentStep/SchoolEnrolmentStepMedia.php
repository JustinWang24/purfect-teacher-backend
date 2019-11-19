<?php

namespace App\Models\EnrolmentStep;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_enrolment_step_id
 * @property int $media_id
 */
class SchoolEnrolmentStepMedia extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'school_enrolment_step_medias';

    /**
     * @var array
     */
    protected $fillable = ['school_enrolment_step_id', 'media_id'];

}
