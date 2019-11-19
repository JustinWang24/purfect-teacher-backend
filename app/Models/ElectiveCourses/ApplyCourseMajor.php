<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyCourseMajor extends Model
{
    protected $fillable = [
        'apply_id','major_id','school_id','major_name'
    ];

}
