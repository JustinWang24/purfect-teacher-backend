<?php

namespace App\Models\Schools;

use App\Models\Acl\Role;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;
use App\Utils\Misc\ConfigurationTool;

class Department extends Model
{
    use HasConfigurations;

    protected $fillable = [
        'school_id', 'institute_id', 'name', 'description',
        ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM,
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION,
        ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR,
    ];

    public $casts = [
        ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION => 'boolean'
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
