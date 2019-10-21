<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 10:48 PM
 */

namespace App\Http\Controllers\Operator;
use App\BusinessLogic\DepartmentsListPage\Factory;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\InstituteRequest;

class InstitutesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(InstituteRequest $request){

    }

    public function add(InstituteRequest $request){

    }

    public function update(InstituteRequest $request){

    }

    public function departments(InstituteRequest $request){
        $logic = Factory::GetLogic($request);
        // 查看系的列表

        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['departments'] = $logic->getData();
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();

        return view($logic->getViewPath(), $this->dataForView);
    }
}