<?php
/**
 * 角色管理的控制器类
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(RoleRequest $request){
        $this->dataForView['pageTitle'] = trans('thumbnail.roles');
        return view('admin.roles.list', $this->dataForView);
    }
}
