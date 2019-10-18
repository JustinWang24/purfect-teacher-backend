<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 11:42 PM
 */

namespace App\BusinessLogic\UsersListPage;


use App\BusinessLogic\UsersListPage\Contracts\IUsersListPageLogic;
use App\BusinessLogic\UsersListPage\Impl\RegisteredStudentsListLogic;
use App\User;
use Illuminate\Http\Request;

class Factory
{
    /**
     * @param Request $request
     * @return IUsersListPageLogic|null
     */
    public static function GetLogic(Request $request){
        $instance = null;
        $type = intval($request->get('type'));
        switch ($type){
            case User::TYPE_STUDENT:
                $instance = new RegisteredStudentsListLogic($request);
                break;
            default:
                break;
        }
        return $instance;
    }
}