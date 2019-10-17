<?php

namespace App\Models\Schools;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = [
        'school_id', 'department_id', 'name', 'description'
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
