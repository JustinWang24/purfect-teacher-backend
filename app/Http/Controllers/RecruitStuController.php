<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Http\Requests\validateRecruitStuRequest;
use View;

class RecruitStuController extends Controller
{
    //招生管理
    public function index(Request $request)
    {
        // dd(111);
        //validateRecruitStuRequest
        return view('recruitStu.index');
    }
    
}
