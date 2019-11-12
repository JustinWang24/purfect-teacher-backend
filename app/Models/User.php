<?php

namespace App;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Models\Acl\Role;
use App\Models\Contract\HasDeviceId;
use App\Models\Contract\HasMobilePhone;
use App\Models\Misc\Enquiry;
use App\Models\Schools\RecruitmentPlan;
use App\Models\Students\StudentProfile;
use App\Models\Teachers\TeacherProfile;
use App\Models\Users\GradeUser;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;
use App\Models\RecruitStudent\RegistrationInformatics;

class User extends Authenticatable implements HasMobilePhone, HasDeviceId
{
    use Notifiable, HasRole;

    // 描述用户状态的常量
    const STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED = 1;
    const STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED_TEXT = '等待验证手机号';
    const STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED = 2;
    const STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED_TEXT = '等待验证身份证';
    const STATUS_VERIFIED = 3;
    const STATUS_VERIFIED_TEXT = '身份已验证';

    const TYPE_STUDENT  = 1;
    const TYPE_EMPLOYEE = 2;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    // 生源类型:
    const SOURCE_GENERAL = 1;
    const SOURCE_SELF    = 2;
    const SOURCE_AGENT   = 3;
    const SOURCE_GENERAL_TEXT = '统招';
    const SOURCE_SELF_TEXT    = '自招';
    const SOURCE_AGENT_TEXT   = '中介';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password','mobile_verified_at','mobile','uuid','status','type','name','email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mobile_verified_at' => 'datetime',
    ];

    /**
     * 获取当前用户的角色Slug
     * @return string
     */
    public function getCurrentRoleSlug(){
        return Role::GetRoleSlugByUserType($this->type);
    }

    /**
     * 判断当前用户是否为超级管理员
     * @return bool
     */
    public function isSuperAdmin(){
        return Role::SUPER_ADMIN_SLUG === $this->getCurrentRoleSlug();
    }

    /**
     * 判断当前用户是否为厂家运营人员或更高权限角色
     * @return bool
     */
    public function isOperatorOrAbove(){
        return in_array($this->getCurrentRoleSlug(), [Role::SUPER_ADMIN_SLUG, Role::OPERATOR_SLUG]);
    }

    /**
     * 判断当前用户是否为校方管理员或更高权限角色
     * @return bool
     */
    public function isSchoolAdminOrAbove(){
        return in_array($this->getCurrentRoleSlug(), [Role::SUPER_ADMIN_SLUG, Role::OPERATOR_SLUG, Role::SCHOOL_MANAGER_SLUG]);
    }

    /**
     * 获取用户的默认首页 view
     * @return string
     */
    public function getDefaultView(){
        $viewPath = 'home';
        if($this->isSuperAdmin()){
            // 超级管理员的首页
            $viewPath = 'admin.schools.list';
        }
        elseif($this->isOperatorOrAbove()){
            // 运营部人员的默认首页
            $viewPath = 'operator.school.list';
        }
        elseif ($this->isSchoolAdminOrAbove()){
            // 学校管理员的默认首页
            $viewPath = 'school_manager.school.view';
        }
        return $viewPath;
    }

    public function profile(){
        $roleSlug = $this->getCurrentRoleSlug();
        if($roleSlug === Role::TEACHER_SLUG || $roleSlug === Role::EMPLOYEE_SLUG){
            // 教师或者职工
            return $this->hasOne(TeacherProfile::class,'teacher_id');
        }
        elseif (in_array($this->type, Role::GetStudentUserTypes())){
            // 已认证学生
            return $this->hasOne(StudentProfile::class);
        }
        else{
            return null;
        }
    }

    /**
     * 学生提交的所有的报名表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrationForm(){
        return $this->hasMany(RegistrationInformatics::class);
    }

    /**
     * 学生提交的所有请求
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enquiries(){
        return $this->hasMany(Enquiry::class);
    }

    /**
     * Todo: 需要完成获取学校管理员所关联的学校 ID 的功能
     * @return int
     */
    public function getSchoolId()
    {
        if($this->isStudent() || $this->isTeacher() || $this->isEmployee()){
            return $this->gradeUser->school_id;
        }
        return 0;
    }

    /**
     * 是否用户为学生
     * @return bool
     */
    public function isStudent(){
        return in_array($this->getCurrentRoleSlug(),
            [Role::VERIFIED_USER_STUDENT_SLUG, Role::VERIFIED_USER_CLASS_LEADER_SLUG, Role::VERIFIED_USER_CLASS_SECRETARY_SLUG]);
    }

    /**
     * 是否用户为老师
     * @return bool
     */
    public function isTeacher(){
        return $this->type === Role::TEACHER;
    }

    /**
     * 是否用户为教职工
     * @return bool
     */
    public function isEmployee(){
        return $this->type === Role::EMPLOYEE;
    }

    /**
     * 获取学生的状态文字
     * @return string
     */
    public function getStatusText(){
        $arr = [
            self::STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED=>self::STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED_TEXT,
            self::STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED=>self::STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED_TEXT,
            self::STATUS_VERIFIED=>self::STATUS_VERIFIED_TEXT,
        ];
        return $arr[$this->status];
    }

    /**
     * 获取关联的用户班级
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gradeUser(){
        return $this->hasOne(GradeUser::class);
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDeviceId()
    {
        // TODO: Implement getDeviceId() method.
    }

    /**
     * 当前用户的指定年的其他报名表
     * @param $plan
     * @param null $exceptFrom
     * @return mixed
     */
    public function otherRegistrationForms($plan, $exceptFrom = null){
        $plans = [];
        $year = $plan->year;
        $schoolId = $plan->school_id;

        $query = RecruitmentPlan::where('year',$year)->where('school_id',$schoolId);

        if($exceptFrom){
            $query->where('id','<>',$plan->id);
        }

        $ps = $query->get();
        foreach ($ps as $p) {
            $plans[] = $p->id;
        }

        return RegistrationInformatics::where('user_id',$exceptFrom->user_id)
            ->where('id','<>',$this->id)
            ->whereIn('recruitment_plan_id',$plans)
            ->get();
    }
}
