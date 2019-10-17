<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SchoolRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SchoolRequest $request){

        return view('admin.schools.edit', $this->dataForView);
    }
}
