<?php

namespace App\Models\Schools;

use App\Models\Acl\Role;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\GradeUser;
use App\User;
use App\Models\Courses\CourseMajor;

class Major extends Model
{
    const TYPE_GENERAL_FULL_DAY     = 1; // 普通全日制
    const TYPE_GENERAL_NOT_FULL_DAY = 2; // 普通非全日制
    const TYPE_UNION_FULL_DAY       = 3; // 校企联合办学全日制
    const TYPE_GENERAL_FULL_DAY_TEXT     = '普通全日制';
    const TYPE_GENERAL_NOT_FULL_DAY_TEXT = '普通非全日制';
    const TYPE_UNION_FULL_DAY_TEXT       = '校企联合办学全日制';

    protected $fillable = [
        'school_id', 'department_id', 'name', 'description','last_updated_by','institute_id','campus_id',
        'category_code', // 专业代码: 01
        'period', // 学制: 3年
        'notes', // 本专业的备注
        'type', // 专业的类型
        'advantage_introduction', // 专业优势
        'future', // 毕业前景
        'funding_policy', // 资助政策
    ];

    public $casts = [
        'open'=>'boolean',
        'hot'=>'boolean',
    ];

    /**
     * 返回所有专业的类型定义
     * @return array
     */
    public function AllTypes(){
        return [
            self::TYPE_GENERAL_FULL_DAY => self::TYPE_GENERAL_FULL_DAY_TEXT,
            self::TYPE_GENERAL_NOT_FULL_DAY => self::TYPE_GENERAL_NOT_FULL_DAY_TEXT,
            self::TYPE_UNION_FULL_DAY => self::TYPE_UNION_FULL_DAY_TEXT,
        ];
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function institute(){
        return $this->belongsTo(Institute::class);
    }

    public function campus(){
        return $this->belongsTo(Campus::class);
    }

    public function grades(){
        return $this->hasMany(Grade::class);
    }

    public function plans()
    {
        return $this->hasMany(RecruitmentPlan::class);
    }

    public function courseMajors(){
        return $this->hasMany(CourseMajor::class);
    }

    public function employeesCount(){
        return GradeUser::where('major_id', $this->id)->where('user_type',Role::GetTeacherUserTypes())->count();
    }

    public function studentsCount(){
        return GradeUser::where('major_id', $this->id)->where('user_type',Role::GetStudentUserTypes())->count();
    }
}
