<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyDay extends Model
{
    protected $fillable = [
    'week_id', 'day'
];
    public function slot() {
        return $this->hasMany(ApplyTimeSlot::class,'day_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($applyDay) {
            $applyDay->slot()->delete();
        });
    }

}
