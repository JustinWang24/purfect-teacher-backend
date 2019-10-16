<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolSceneryController extends Controller
{

    /**
     * 学校简介
     */
    public  function profile()
    {
        return view('Teacher.SchoolScenery.profile');
    }



    /**
     * 校园风采
     */
    public  function  index()
    {
        return view('Teacher.SchoolScenery.index');
    }
}
