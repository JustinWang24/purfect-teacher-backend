<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyDayIndex extends Model
{
    protected $fillable = [
    'week_id', 'day_index'
];
    public function slot() {
        return $this->hasMany(ApplyTimeSlot::class);
    }
}
