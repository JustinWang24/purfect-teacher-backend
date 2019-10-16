<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecruitStuRequest;

class RecruitStuController extends Controller
{
    public function index(RecruitStuRequest $requets)
    {
        return view('recruitStu.index');
    }

}
