<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyWeek extends Model
{
    protected $fillable = [
        'group_id', 'week'
    ];

    public function day() {
        return $this->hasMany(ApplyDayIndex::class);
    }
}
