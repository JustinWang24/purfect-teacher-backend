<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class GetJobController extends Controller
{
    //主页
    public function index(Request $request)
    {
        return view('getJob.index');
    }
}
