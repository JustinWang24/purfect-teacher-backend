<?php

namespace App\Models\Schools;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'school_id', 'institute_id', 'name', 'description'
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function institute(){
        return $this->belongsTo(Institute::class);
    }
}
