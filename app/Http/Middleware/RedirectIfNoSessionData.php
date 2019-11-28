<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class RedirectIfNoSessionData
{
    private $specialRoutes = [
        'home',
        'operator.schools.enter',
        'admin.create.school-manager',
        'admin.edit.school-manager',
        'logout',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(
            empty($request->session()->get('school.id'))
            && $request->user() && $request->user()->isOperatorOrAbove()
        ){
            if(!in_array(Route::currentRouteName(), $this->specialRoutes)){
                // 如果申请的不是 home, 并且 session 中没有 school.id, 那么就要跳转到 home, 以从新选择要操作的学校.
                // 这个操作是针对超级管理员和 Operator 的
                return redirect()->route('home');
            }
        }
        return $next($request);
    }
}
