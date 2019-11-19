<?php

namespace App\Models\EnrolmentStep;

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
    protected $fillable = ['name', 'enrolment_step_id', 'school_id', 'campus_id', 'describe', 'sort', 'user_id', 'created_at', 'updated_at'];

}
