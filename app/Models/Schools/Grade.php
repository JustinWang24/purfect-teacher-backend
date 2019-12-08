<?php

namespace App\Models\Schools;

use App\Models\Acl\Role;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;

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

    public function studentsCount(){
        return GradeUser::where('grade_id', $this->id)->where('user_type',Role::GetStudentUserTypes())->count();
    }

    /**
     * 班级管理员, 包括班主任和班长
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gradeManager(){
        return $this->hasOne(GradeManager::class,'grade_id','id');
    }
}
