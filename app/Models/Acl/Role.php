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
    const OPERATOR              = 3;  // 日常操作人员

    // 系统定义的用户身份: 校内用户相关
    const VISITOR               = 4;  // 未注册用户
    const REGISTERED_USER       = 5;  // 已注册用户
    const VERIFIED_USER_STUDENT             = 6;  // 已认证的用户 学生
    const VERIFIED_USER_CLASS_LEADER        = 7;  // 已认证的用户 班长
    const VERIFIED_USER_CLASS_SECRETARY     = 8;  // 已认证的用户 团支书

    const TEACHER               = 9;   // 已认证的教师
    const EMPLOYEE              = 10;  // 已认证的教工
    const SCHOOL_MANAGER        = 11;  // 已认证的管理员 (日常教学管理岗)
    const COMPANY               = 12;  // 已认证的企业 招生的用人单位等
    const DELIVERY              = 13;  // 已认证的配送员

    const BUSINESS_INNER        = 14;  // 已认证 校内商家
    const BUSINESS_OUTER        = 15;  // 已认证 校外商家

    const SUPER_ADMIN_SLUG           = 'su';  // 系统超级管理员
    const ADMINISTRATOR_SLUG         = 'school_admin';  // 学校管理员
    const OPERATOR_SLUG              = 'operator';  // 日常操作人员

    // 系统定义的用户身份: 校内用户相关
    const VISITOR_SLUG               = 'visitor';  // 未注册用户
    const REGISTERED_USER_SLUG       = 'registered';  // 已注册用户
    const VERIFIED_USER_STUDENT_SLUG             = 'verified_student';  // 已认证的用户 学生
    const VERIFIED_USER_CLASS_LEADER_SLUG        = 'verified_class_leader';  // 已认证的用户 班长
    const VERIFIED_USER_CLASS_SECRETARY_SLUG     = 'verified_class_secretary';  // 已认证的用户 团支书

    const TEACHER_SLUG               = 'teacher';   // 已认证的教师
    const EMPLOYEE_SLUG              = 'school_employee';  // 已认证的教工
    const SCHOOL_MANAGER_SLUG        = 'school_manager';  // 已认证的管理员 (日常教学管理岗)
    const COMPANY_SLUG               = 'company';  // 已认证的企业 招生的用人单位等
    const DELIVERY_SLUG              = 'delivery';  // 已认证的配送员

    const BUSINESS_INNER_SLUG        = 'business_inner';  // 已认证 校内商家
    const BUSINESS_OUTER_SLUG        = 'business_outer';  // 已认证 校外商家
}