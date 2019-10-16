<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class AccountNumberController extends Controller
{
    // 校园风光
    public function index(Request $request)
    {
        return view('accountNumber.index');
    }
}
