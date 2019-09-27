<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Login
{

    public function __construct()
    {

    }


    public function handle($request, Closure $next, $guard = null)
    {
        $loginInfo = app('session')->get('loginInfo');

        if(empty($loginInfo)){
            if($request->ajax()){
                return response()->json(['status'=>1, 'info'=>'请登录', 'data'=>'']);
            }

            return redirect()->route('login');
        }

        view()->share('loginInfo', $loginInfo);
        return $next($request);
    }
}
