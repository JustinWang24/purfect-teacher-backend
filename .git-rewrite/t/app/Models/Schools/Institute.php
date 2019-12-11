<?php

namespace App\Models\Schools;

use App\Models\Acl\Role;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;
use App\User;
use App\Models\School;

class Institute extends Model
{
    protected $fillable = [
        'school_id', 'campus_id', 'name', 'description','last_updated_by',
        'category_code', // 学院代码: 01
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
        return GradeUser::where('institute_id', $this->id)->whereIn('user_type',Role::GetTeacherUserTypes())->count();
    }

    public function studentsCount(){
        return GradeUser::where('institute_id', $this->id)->whereIn('user_type',Role::GetStudentUserTypes())->count();
    }
}
