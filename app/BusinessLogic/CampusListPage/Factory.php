<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 9:00 AM
 */

namespace App\BusinessLogic\CampusListPage;
use App\BusinessLogic\CampusListPage\Impl\CampusListPageLogic;
use Illuminate\Http\Request;
use App\BusinessLogic\UsersListPage\Impl\AbstractDataListLogic;

class Factory
{
    /**
     * @param Request $request
     * @return AbstractDataListLogic
     */
    public static function GetLogic(Request $request){
        return new CampusListPageLogic($request);
    }
}