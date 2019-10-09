<?php
/**
 * Created by Justin
 */

namespace App\Models\Acl;
use Kodeine\Acl\Models\Eloquent\Role as BaseRole;

class Role extends BaseRole
{
    // 系统定义的用户身份: 日常运营管理相关
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
}