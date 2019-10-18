<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;
use App\User;
use App\Models\School;

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

    public function departments(){
        return $this->hasMany(Department::class);
    }

    public function employeesCount(){
        return GradeUser::where('institute_id', $this->id)->where('user_type',User::TYPE_EMPLOYEE)->count();
    }

    public function studentsCount(){
        return GradeUser::where('institute_id', $this->id)->where('user_type',User::TYPE_STUDENT)->count();
    }
}
