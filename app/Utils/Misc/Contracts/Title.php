<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/11/19
 * Time: 9:24 PM
 */

namespace App\Utils\Misc\Contracts;


interface Title
{
    const LEADER    = 1; // 部门领导
    const DEPUTY    = 2; // 部门副手
    const MEMBER    = 3; // 部门职员

    const LEADER_TXT    = '部门正职领导';
    const DEPUTY_TXT    = '部门副职领导';
    const MEMBER_TXT    = '教师/职工';
    const ALL_TXT    = '全部成员';

    /**
     * 以下定义用于在发送消息通知时, 定位消息的接收者
     */
    const ALL = '全部成员';
    const ORGANIZATION_EMPLOYEE = '教师/职工';
    const CLASS_ADVISER = '班主任';
    const GRADE_ADVISER = '年级组长';
    const ORGANIZATION_DEPUTY = '部门副职领导'; // 用户所在科室/机构/教研组的副职领导
    const ORGANIZATION_LEADER = '部门正职领导';
    const DEPARTMENT_LEADER = '系主任';
    const SCHOOL_DEPUTY = '副校长';
    const SCHOOL_PRINCIPAL = '校长';
    const SCHOOL_COORDINATOR = '书记';

    /**
     * 以下定义用于在发送消息通知时, 定位消息的接收者
     */
    const ALL_ID                   = 0;
    const ORGANIZATION_LEADER_ID   = 1;
    const ORGANIZATION_DEPUTY_ID   = 2; // 用户所在科室/机构/教研组的副职领导
    const ORGANIZATION_EMPLOYEE_ID = 3;

    const CLASS_ADVISER_ID      = 20;
    const GRADE_ADVISER_ID      = 21;
    const DEPARTMENT_LEADER_ID  = 22;

    const SCHOOL_DEPUTY_ID      = 30;
    const SCHOOL_PRINCIPAL_ID   = 31;
    const SCHOOL_COORDINATOR_ID = 32;
}