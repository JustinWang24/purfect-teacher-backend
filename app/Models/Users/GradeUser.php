<?php

namespace App\Models\Users;

use App\Models\Misc\Enquiry;
use App\Models\School;
use App\Models\Schools\Campus;
use App\Models\Schools\Department;
use App\Models\Schools\DepartmentAdviser;
use App\Models\Schools\Grade;
use App\Models\Schools\GradeManager;
use App\Models\Schools\Institute;
use App\Models\Schools\Major;
use App\Models\Students\StudentProfile;
use App\Models\Teachers\TeacherProfile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GradeUser extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'name',
        'school_id',
        'campus_id',
        'institute_id',
        'department_id',
        'major_id',
        'grade_id',
        'last_updated_by',
    ];

    /**
     * 关联的学校
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(){
        return $this->belongsTo(School::class);
    }

    /**
     * 关联的校区
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campus(){
        return $this->belongsTo(Campus::class);
    }

    /**
     * 关联的学院
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institute(){
        return $this->belongsTo(Institute::class);
    }

    /**
     * 关联的系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(){
        return $this->belongsTo(Department::class);
    }

    /**
     * 关联的专业
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function major(){
        return $this->belongsTo(Major::class);
    }

    /**
     * 关联的班级
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    /**
     * 班主任和班长
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gradeManager(){
        return $this->hasOne(GradeManager::class, 'grade_id','grade_id');
    }

    /**
     * 系主任的关联
     */
    public function departmentAdviser(){
        return $this->hasOne(DepartmentAdviser::class, 'department_id','department_id');
    }

    /**
     * 关联的用户, 老师/学生/商户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function teacherProfile(){
        return $this->hasOne(TeacherProfile::class,'user_id','user_id');
    }

    public function studentProfile(){
        return $this->hasOne(StudentProfile::class,'user_id','user_id');
    }

    public function enquiries(){
        return $this->hasMany(Enquiry::class, 'user_id','user_id');
    }

    /**
     * 返回教职工所工作的地方的描述
     * @return string
     */
    public function workAt(){
        $instituteStr =  $this->institute->name . ' / ';
        $deptStr = $this->department_id > 0 ? $this->department->name . ' / ' : '';
        $majorStr = $this->major_id > 0 ? $this->major->name . ' / ' : '';
        $gradeStr = $this->grade_id > 0 ? $this->grade->name . ' / ' : '';
        return $instituteStr.$deptStr.$majorStr.$gradeStr;
    }

    /**
     * 返回教职工所工作的地方的描述
     * @return string
     */
    public function studyAt(){
        $instituteStr =  $this->institute->name . ' / ';
        $deptStr = $this->department_id > 0 ? $this->department->name . ' / ' : '';
        $majorStr = $this->major_id > 0 ? $this->major->name . ' / ' : '';
        $gradeStr = $this->grade_id > 0 ? $this->grade->name . ' / ' : '';
        return $instituteStr.$deptStr.$majorStr.$gradeStr;
    }
}
