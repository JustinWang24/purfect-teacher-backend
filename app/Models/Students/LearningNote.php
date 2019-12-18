<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;

class LearningNote extends Model
{
    protected $fillable = [
        'student_id',
        'year',
        'term',
        'timetable_item_id',
        'note',
    ];
}