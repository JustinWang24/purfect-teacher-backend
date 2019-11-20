<?php

namespace App\Models\EnrolmentStep;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_enrolment_step_id
 * @property int $user_id
 */
class SchoolEnrolmentStepAssist extends Model
{

    public $timestamps = false;

    public $field = ['id','uuid','name','mobile'];
    /**
     * @var array
     */
    protected $fillable = ['school_enrolment_step_id', 'user_id'];


    public function user() {
        return $this->belongsTo(User::class,'user_id')->select($this->field);
    }

}
