<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Models\Acl\Role;

class RoutesGuard
{
    private $routesPrefixToBeProtected = [
        'admin'                     =>[Role::SUPER_ADMIN_SLUG],
        Role::OPERATOR_SLUG         =>[Role::OPERATOR_SLUG, Role::SUPER_ADMIN_SLUG],
        Role::ADMINISTRATOR_SLUG    =>[Role::OPERATOR_SLUG, Role::SUPER_ADMIN_SLUG, Role::ADMINISTRATOR_SLUG],
        Role::OFFICE_MANAGER_SLUG   =>[Role::OPERATOR_SLUG, Role::SUPER_ADMIN_SLUG, Role::ADMINISTRATOR_SLUG, Role::OFFICE_MANAGER_SLUG],
        Role::TEACHER_SLUG          =>[Role::OPERATOR_SLUG, Role::SUPER_ADMIN_SLUG, Role::ADMINISTRATOR_SLUG, Role::SCHOOL_MANAGER_SLUG, Role::TEACHER_SLUG],
        Role::VERIFIED_USER_STUDENT_SLUG =>[Role::OPERATOR_SLUG, Role::SUPER_ADMIN_SLUG, Role::ADMINISTRATOR_SLUG, Role::SCHOOL_MANAGER_SLUG, Role::TEACHER_SLUG, Role::VERIFIED_USER_STUDENT_SLUG]
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
        $currentRoute = Route::currentRouteName();
        // 获取当前路由的第一个单词, 判断是否是属于被保护的范围
        $firstWord = $this->getFirstWordOfCurrentRouteName($currentRoute);
        if(in_array($firstWord, array_keys($this->routesPrefixToBeProtected))){
            // 表示属于被保护的路由范畴. 那么检查一下, 当前用户是否登陆
            /**
             * @var User $user
             */
            $user = $request->user();
            if(!$user || !in_array($user->getCurrentRoleSlug() , $this->routesPrefixToBeProtected[$firstWord])){
                // 当前用户没有登陆, 或者不允许访问所申请的资源, 因此直接导向到home
                return redirect('/home');
            }
        }

        return $next($request);
    }

    private function getFirstWordOfCurrentRouteName($name){
        $arr = explode('.',$name);
        return $arr[0] ?? 'n.a';
    }
}
