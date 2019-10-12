<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class TrainController extends Controller
{
    //主页
    public function index(Request $request)
    {
        return view('train.index');
    }
}
