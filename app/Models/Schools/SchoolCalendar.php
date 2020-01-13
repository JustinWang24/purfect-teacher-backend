<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class SchoolCalendar extends Model
{
    protected $fillable = ['school_id', 'tag', 'content', 'event_time', 'week_idx', 'term', 'year'];

    protected $casts = [
        'tag' => 'array',
        'event_time' => 'datetime:Y-m-d',
    ];
}
