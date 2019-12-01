<?php

namespace App\Models\Teachers\Performance;

use Illuminate\Database\Eloquent\Model;

class TeacherPerformanceConfig extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
    ];
    public $timestamps = false;
}