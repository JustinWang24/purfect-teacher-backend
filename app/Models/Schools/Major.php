<?php

namespace App\Models\Schools;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;
use App\User;

class Major extends Model
{
    protected $fillable = [
        'school_id', 'department_id', 'name', 'description','last_updated_by'
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function grades(){
        return $this->hasMany(Grade::class);
    }

    public function employeesCount(){
        return GradeUser::where('major_id', $this->id)->where('user_type',User::TYPE_EMPLOYEE)->count();
    }

    public function studentsCount(){
        return GradeUser::where('major_id', $this->id)->where('user_type',User::TYPE_STUDENT)->count();
    }
}
