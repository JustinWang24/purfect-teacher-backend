<?php

namespace App\Models\Timetable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimetableItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year','term',
        'course_id','time_slot_id',
        'building_id','room_id',
        'teacher_id','grade_id',
        'weekday_index','repeat_unit',
        'at_special_datetime','to_special_datetime',
        'to_replace','last_updated_by',
        'school_id',
    ];
}
