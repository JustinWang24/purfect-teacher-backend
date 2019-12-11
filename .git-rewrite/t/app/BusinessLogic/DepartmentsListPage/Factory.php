<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 8:02 AM
 */

namespace App\BusinessLogic\DepartmentsListPage;


use App\BusinessLogic\DepartmentsListPage\Impl\DepartmentsListPageLogic;
use Illuminate\Http\Request;
use App\BusinessLogic\UsersListPage\Impl\AbstractDataListLogic;

class Factory
{
    /**
     * @param Request $request
     * @return AbstractDataListLogic
     */
    public static function GetLogic(Request $request){
        return new DepartmentsListPageLogic($request);
    }
}