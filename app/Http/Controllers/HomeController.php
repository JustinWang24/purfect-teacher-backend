<?php

namespace App\Http\Controllers;

use App\BusinessLogic\HomePage\Factory;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\BusinessLogic\DocumentsWorkflows\Factory as DocumentFactory;

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


    public function login()
    {
        return view('auth.login');
    }


    /**
     * Show the application dashboard.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Todo: 用户登陆成功, 应该根据不同的用户角色, 跳转到不同的起始页
        /**
         * @var User $user
         */
        $user = $request->user();
        $logic = Factory::GetLogic($request);
        $data = $logic ? $logic->getDataForView() : [];
        $this->dataForView['pageTitle'] = '首页';
        return view($user->getDefaultView(), array_merge($this->dataForView, $data));
    }

    /**
     * 处理任意约定以步骤的方法
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handle_step(Request $request)
    {
        $predefinedStep = DocumentFactory::GetInstance($request);

        $predefinedStep->checkDocument($request->get('document'));

        if($predefinedStep->isDone()){
            $this->dataForView['next'] = $predefinedStep->passToNext();
        }

        return view('',$this->dataForView);
    }

}
