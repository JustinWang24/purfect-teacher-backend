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
        $this->dataForView['pageTitle'] = '学校简介';
        return view('Teacher.SchoolScenery.profile', $this->dataForView);
    }



    /**
     * 校园风采
     */
    public  function  index()
    {
        $this->dataForView['pageTitle'] = '校园相册';
        return view('Teacher.SchoolScenery.index', $this->dataForView);
    }
}
