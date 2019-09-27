<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Http\Service\ProxySrv;
use Closure;

class ProxyStatus
{

    public function __construct()
    {

    }


    public function handle($request, Closure $next, $guard = null)
    {
        $proxySrv = new ProxySrv();
        $pingStatus = $proxySrv->pingStatus();

        if(empty($pingStatus)){
            throw new ApiException("代理服务器状态错误");
        }

        return $next($request);
    }
}
