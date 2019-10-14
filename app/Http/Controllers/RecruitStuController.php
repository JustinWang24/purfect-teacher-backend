<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecruitStuRequest;
use View;
use app\Http\Resources\RecruitStu;


class RecruitStuController extends Controller
{
    //招生管理
    public function index(RecruitStuRequest $requets)
    {
        return view('recruitStu.index');
    }
    
}
