<?php

namespace App\Models\AttendanceSchedules;

use App\Models\Schools\Grade;
use Illuminate\Database\Eloquent\Model;

class SpecialAttendance extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'school_id',
        'start_date',
        'end_date',
        'high_level',
        'middle_level',
        'teacher_level',
        'grade_id',
        'related_organizations',
        'description',
    ];

    public $casts = [
        'start_date'=>'datetime:Y-m-d',
        'end_date'=>'datetime:Y-m-d',
        'related_organizations'=>'array'
    ];

    public function grade(){
        return $this->belongsTo(Grade::class)->select(['id','name']);
    }
}