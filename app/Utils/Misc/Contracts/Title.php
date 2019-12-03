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

    const LEADER_TXT    = '部门领导';
    const DEPUTY_TXT    = '部门副职';
    const MEMBER_TXT    = '部门职员';
}