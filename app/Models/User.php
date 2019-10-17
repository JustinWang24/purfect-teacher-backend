<?php

namespace App;

use App\Models\Acl\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;

class User extends Authenticatable
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password','mobile_verified_at','mobile','uuid','status','type'
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
            $viewPath = 'admin.schools.list';
        }
        return $viewPath;
    }
}
