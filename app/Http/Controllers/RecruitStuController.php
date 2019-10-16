<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecruitStuRequest;

class RecruitStuController extends Controller
{
    //招生管理
    public function index(RecruitStuRequest $requets)
    {
        return view('recruitStu.index');
    }
    
}
