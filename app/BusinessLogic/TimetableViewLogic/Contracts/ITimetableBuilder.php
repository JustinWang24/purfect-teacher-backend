<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 3:52 PM
 */

namespace App\BusinessLogic\TimetableViewLogic\Contracts;

interface ITimetableBuilder
{
    const STUDENT_REQUEST_TYPE_DAILY = 'daily'; // 为用户提取课程表的时候, 是提取一天的
    const STUDENT_REQUEST_TYPE_WEEKLY = 'weekly'; // 为用户提取课程表的时候, 是提取一周的

    public function build();
}