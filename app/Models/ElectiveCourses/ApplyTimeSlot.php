<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyTimeSlot extends Model
{
    protected $fillable = [
        'day_id', 'time_slot_id'
    ];
}
