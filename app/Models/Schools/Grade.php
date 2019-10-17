<?php

namespace App\Models\Schools;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'school_id', 'major_id', 'name', 'description',
        'year', // 代表哪一届的学生
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }
}
