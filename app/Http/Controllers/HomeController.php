<?php

namespace App\Http\Controllers;

use App\BusinessLogic\HomePage\Factory;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->dataForView['needChart'] = true;
        // Todo: 用户登陆成功, 应该根据不同的用户角色, 跳转到不同的起始页
        /**
         * @var User $user
         */
        $user = $request->user();

        $logic = Factory::GetLogic($user);

        $data = $logic ? $logic->getDataForView() : [];
        return view($user->getDefaultView(), array_merge($this->dataForView, $data));
    }
}
