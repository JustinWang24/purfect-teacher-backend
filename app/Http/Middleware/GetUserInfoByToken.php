<?php

namespace App\Http\Middleware;

use Closure;
use App\Dao\Users\UserDao;
use Illuminate\Http\Request;
use App\Utils\JsonBuilder;
use App\Http\Requests\MyStandardRequest;

class GetUserInfoByToken
{
    /**
     * Handle an incoming request.
     *
     * @param  MyStandardRequest  $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $dao = new UserDao;
//        if ($request->token) {
//            $user = $dao->getUserByApiToken($request->token);
//            $request['user'] = $user;
//
//        } else {
//
//        }
         return $next($request);
    }
}
