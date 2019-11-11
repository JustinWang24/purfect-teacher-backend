<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\EmployRequest;

class EmployController extends Controller
{

    /***
     * 获取所有未分配的学生
     * @param EmployRequest $request
     */
    public function index(EmployRequest $request)
    {
        $schoolId = $request->getSchoolId();
        dd($schoolId);
    }



}
