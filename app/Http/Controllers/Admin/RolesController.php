<?php
/**
 * 角色管理的控制器类
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{

    public function index(RoleRequest $request){
        dd(get_class($request));
    }
}
