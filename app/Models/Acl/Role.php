<?php
/**
 * Created by Justin
 */

namespace App\Models\Acl;
use Kodeine\Acl\Models\Eloquent\Role as BaseRole;

class Role extends BaseRole
{
    // 系统定义的用户身份: 日常运营管理相关 (以下的常量值, 应永远与 roles 表中对应的记录值相同)
    const SUPER_ADMIN           = 1;  // 系统超级管理员
    const ADMINISTRATOR         = 2;  // 学校管理员
    const SCHOOL_MANAGER        = 2;  // 学校管理员 (日常教学管理岗)
    const OPERATOR              = 3;  // 日常操作人员

    // 系统定义的用户身份: 校内用户相关
    const VISITOR               = 4;  // 未注册用户
    const REGISTERED_USER       = 5;  // 已注册用户
    const VERIFIED_USER_STUDENT             = 6;  // 已认证的用户 学生
    const VERIFIED_USER_CLASS_LEADER        = 7;  // 已认证的用户 班长
    const VERIFIED_USER_CLASS_SECRETARY     = 8;  // 已认证的用户 团支书

    const TEACHER               = 9;   // 已认证的教师
    const EMPLOYEE              = 10;  // 已认证的教工
    const OFFICE_MANAGER        = 11;  // 已认证的管理员 (日常教学管理岗)
    const COMPANY               = 12;  // 已认证的企业 招生的用人单位等
    const DELIVERY              = 13;  // 已认证的配送员

    const BUSINESS_INNER        = 14;  // 已认证 校内商家
    const BUSINESS_OUTER        = 15;  // 已认证 校外商家

    const SUPER_ADMIN_SLUG           = 'su';  // 系统超级管理员
    const ADMINISTRATOR_SLUG         = 'school_manager';  // 学校管理员
    const SCHOOL_MANAGER_SLUG        = 'school_manager';  // 学校管理员, 由于一个以前的一个失误, 因此从新命名了
    const OPERATOR_SLUG              = 'operator';  // 日常操作人员

    // 系统定义的用户身份: 校内用户相关
    const VISITOR_SLUG               = 'visitor';  // 未注册用户
    const REGISTERED_USER_SLUG       = 'registered';  // 已注册用户
    const VERIFIED_USER_STUDENT_SLUG             = 'verified_student';  // 已认证的用户 学生
    const VERIFIED_USER_CLASS_LEADER_SLUG        = 'verified_class_leader';  // 已认证的用户 班长
    const VERIFIED_USER_CLASS_SECRETARY_SLUG     = 'verified_class_secretary';  // 已认证的用户 团支书

    const TEACHER_SLUG               = 'teacher';   // 已认证的教师
    const EMPLOYEE_SLUG              = 'school_employee';  // 已认证的教工
    const OFFICE_MANAGER_SLUG        = 'office_manager';  // 已认证的管理员 (日常教学管理岗)
    const COMPANY_SLUG               = 'company';  // 已认证的企业 招生的用人单位等
    const DELIVERY_SLUG              = 'delivery';  // 已认证的配送员

    const BUSINESS_INNER_SLUG        = 'business_inner';  // 已认证 校内商家
    const BUSINESS_OUTER_SLUG        = 'business_outer';  // 已认证 校外商家

    // 系统定义的用户身份文字: 校内用户相关
    const SUPER_ADMIN_TEXT           = '系统超级管理员';  // 系统超级管理员
    const ADMINISTRATOR_TEXT         = '学校管理员';  // 学校管理员
    const OPERATOR_TEXT              = '日常操作人员';  // 日常操作人员
    const VISITOR_TEXT               = '未注册用户';  // 未注册用户
    const REGISTERED_USER_TEXT       = '已注册用户';  // 已注册用户
    const VERIFIED_USER_STUDENT_TEXT             = '已认证的用户 学生';  // 已认证的用户 学生
    const VERIFIED_USER_CLASS_LEADER_TEXT        = '已认证的用户 班长';  // 已认证的用户 班长
    const VERIFIED_USER_CLASS_SECRETARY_TEXT     = '已认证的用户 团支书';  // 已认证的用户 团支书

    const TEACHER_TEXT               = '已认证的教师';   // 已认证的教师
    const EMPLOYEE_TEXT              = '已认证的教工';  // 已认证的教工
    const SCHOOL_MANAGER_TEXT        = '已认证的部门管理员 (日常教学管理岗)';  // 已认证的管理员 (日常教学管理岗)
    const COMPANY_TEXT               = '已认证的企业 招生的用人单位';  // 已认证的企业 招生的用人单位等
    const DELIVERY_TEXT              = '已认证的配送员';  // 已认证的配送员

    const BUSINESS_INNER_TEXT        = '已认证 校内商家';  // 已认证 校内商家
    const BUSINESS_OUTER_TEXT        = '已认证 校外商家';  // 已认证 校外商家

    // 特殊的角色

    public $timestamps = false;

    /**
     * 返回指定角色的 slug 字符串
     * @param $type
     * @return string
     */
    public static function GetRoleSlugByUserType($type){
        return self::AllTypes()[$type] ?? self::TEACHER_SLUG;
    }

    /**
     * 获取所有的已定义角色列表
     * @return array
     */
    public static function AllTypes(){
        return [
            self::SUPER_ADMIN => self::SUPER_ADMIN_SLUG,
            self::ADMINISTRATOR => self::ADMINISTRATOR_SLUG,
            self::SCHOOL_MANAGER => self::SCHOOL_MANAGER_SLUG,
            self::OPERATOR => self::OPERATOR_SLUG,
            self::VISITOR => self::VISITOR_SLUG,
            self::REGISTERED_USER => self::REGISTERED_USER_SLUG,
            self::VERIFIED_USER_STUDENT => self::VERIFIED_USER_STUDENT_SLUG,
            self::VERIFIED_USER_CLASS_LEADER => self::VERIFIED_USER_CLASS_LEADER_SLUG,
            self::VERIFIED_USER_CLASS_SECRETARY => self::VERIFIED_USER_CLASS_SECRETARY_SLUG,
            self::TEACHER => self::TEACHER_SLUG,
            self::EMPLOYEE => self::EMPLOYEE_SLUG,
            self::OFFICE_MANAGER => self::OFFICE_MANAGER_SLUG,
            self::COMPANY => self::COMPANY_SLUG,
            self::DELIVERY => self::DELIVERY_SLUG,
            self::BUSINESS_INNER => self::BUSINESS_INNER_SLUG,
            self::BUSINESS_OUTER => self::BUSINESS_OUTER_SLUG,
        ];
    }

    /**
     * 获取所有的已定义角色列表
     * @param bool $withSuper
     * @return array
     */
    public static function AllNames($withSuper = false){
        $roles = [
            self::ADMINISTRATOR_SLUG => self::ADMINISTRATOR_TEXT,
            self::OPERATOR_SLUG => self::OPERATOR_TEXT,
            self::VISITOR_SLUG => self::VISITOR_TEXT,
            self::REGISTERED_USER_SLUG => self::REGISTERED_USER_TEXT,
            self::VERIFIED_USER_STUDENT_SLUG => self::VERIFIED_USER_STUDENT_TEXT,
            self::VERIFIED_USER_CLASS_LEADER_SLUG => self::VERIFIED_USER_CLASS_LEADER_TEXT,
            self::VERIFIED_USER_CLASS_SECRETARY_SLUG => self::VERIFIED_USER_CLASS_SECRETARY_TEXT,
            self::TEACHER_SLUG => self::TEACHER_TEXT,
            self::EMPLOYEE_SLUG => self::EMPLOYEE_TEXT,
            self::OFFICE_MANAGER_SLUG => self::SCHOOL_MANAGER_TEXT,
            self::COMPANY_SLUG => self::COMPANY_TEXT,
            self::DELIVERY_SLUG => self::DELIVERY_TEXT,
            self::BUSINESS_INNER_SLUG => self::BUSINESS_INNER_TEXT,
            self::BUSINESS_OUTER_SLUG => self::BUSINESS_OUTER_TEXT,
        ];

        if($withSuper){
            $roles[self::SUPER_ADMIN_SLUG] = self::SUPER_ADMIN_TEXT;
        }

        return $roles;
    }

    public static function GetStudentUserTypes(){
        return [
            Role::VERIFIED_USER_STUDENT, Role::VERIFIED_USER_CLASS_LEADER, Role::VERIFIED_USER_CLASS_SECRETARY
        ];
    }

    public static function GetTeacherUserTypes(){
        return [
            Role::TEACHER, Role::EMPLOYEE
        ];
    }
}
