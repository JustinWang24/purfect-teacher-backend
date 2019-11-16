<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyWeek extends Model
{
    protected $fillable = [
        'group_id', 'week'
    ];

    public function day() {
        return $this->hasMany(ApplyDay::class,'week_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($applyWeek) {
            $applyWeek->day()->delete();
        });
    }
}
