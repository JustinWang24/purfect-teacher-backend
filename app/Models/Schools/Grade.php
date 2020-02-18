<?php

namespace App\Models\Schools;

use App\Models\Acl\Role;
use App\Models\School;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
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
        return GradeUser::where('grade_id', $this->id)->whereIn('user_type',Role::GetStudentUserTypes())->count();
    }

    public function gradeUser()
    {
        return $this->hasMany(GradeUser::class);
    }

    public function gradeResource()
    {
        return $this->hasMany(GradeResource::class);
    }

    /**
     * 班级管理员, 包括班主任和班长
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gradeManager(){
        return $this->hasOne(GradeManager::class,'grade_id','id');
    }

    /**
     * 班级中所有学生
     * @return User[]
     */
    public function allStudents(){
        $studentsIds = [];
        foreach ($this->gradeUser as $gu) {
            /**
             * @var GradeUser $gu
             */
            if($gu->isStudent()){
                $studentsIds[] = $gu->user_id;
            }
        }
        return User::whereIn('id',$studentsIds)->orderBy('name','asc')->get();
    }

    /**
     * 班级年级
     * @return int
     */
    public function gradeYear() {
        $yearAndTerm = GradeAndYearUtil::GetYearAndTerm(Carbon::now());
        return $yearAndTerm['year'] - $this->year + 1 ;
    }
}
