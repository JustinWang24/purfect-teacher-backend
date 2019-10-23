<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 9:12 PM
 */

namespace App\Http\Controllers\Operator;

use App\BusinessLogic\UsersListPage\Factory;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 加载用户列表的方法
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(MyStandardRequest $request){
        $logic = Factory::GetLogic($request);
        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['returnPath'] = $logic->getReturnPath();
        // 给 Pagination 用
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();
        return view($logic->getViewPath(), array_merge($this->dataForView, $logic->getUsers()));
    }
}
