<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolSceneryController extends Controller
{

    /**
     * 校园风采
     */
    public  function  index()
    {
        return view('Teacher.SchoolScenery.index');
    }
}
