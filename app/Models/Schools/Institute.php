<?php

namespace App\Models\Schools;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    protected $fillable = [
        'school_id', 'campus_id', 'name', 'description'
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function campus(){
        return $this->belongsTo(Campus::class);
    }
}
