<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use app\Http\Resources\RecruitStu;

class RecruitStuController extends Controller
{
    //招生管理
    public function index(Request $request)
    {
        // dd(111);
        return view('recruitStu.index');
    }
    
}
