<?php

namespace App\Models\Schools;

use App\Models\Acl\Role;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;
use App\User;

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

    public function majors(){
        return $this->hasMany(Major::class);
    }

    public function employeesCount(){
        return GradeUser::where('department_id', $this->id)->where('user_type',Role::GetTeacherUserTypes())->count();
    }

    public function studentsCount(){
        return GradeUser::where('department_id', $this->id)->where('user_type',Role::GetStudentUserTypes())->count();
    }
}
