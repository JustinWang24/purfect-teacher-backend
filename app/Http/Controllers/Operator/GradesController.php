<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\School\GradeRequest;
use App\Http\Controllers\Controller;
use App\BusinessLogic\UsersListPage\Factory;

class GradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(GradeRequest $request){

    }

    public function edit(GradeRequest $request){

    }

    public function update(GradeRequest $request){

    }

    /**
     * 从班级的角度, 加载给定班级的学生列表
     * @param GradeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(GradeRequest $request){
        $logic = Factory::GetLogic($request);
        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();
        $this->dataForView['returnPath'] = $logic->getReturnPath();
        return view($logic->getViewPath(),array_merge($this->dataForView, $logic->getUsers()));
    }
}
