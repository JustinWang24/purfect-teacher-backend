<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class EquipmentController extends Controller
{
    //主页
    public function index(Request $request)
    {
        return view('equipment.index');
    }
}
