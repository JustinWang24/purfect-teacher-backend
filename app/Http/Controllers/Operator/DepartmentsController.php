<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\School\DepartmentRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessLogic\CampusListPage\Factory;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function majors(DepartmentRequest $request){
        $logic = Factory::GetLogic($request);
        // 查看系的列表

        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['majors'] = $logic->getData();
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();

        return view($logic->getViewPath(), $this->dataForView);
    }
}
