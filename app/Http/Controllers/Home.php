<?php

namespace App\Http\Controllers;

use App\Http\Service\ProxySrv;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class Home extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(){
        $loginInfo = app('session')->get('loginInfo');
        $isLogin = 0;
        if(!empty($loginInfo['username'])){
            $isLogin = 1;
        }

        $viewData = [
            'isLogin'=>$isLogin
        ];

        return view('admin.login', $viewData);

    }


    public function loginDo(Request $request){

        $params = $request->toArray();
        if(empty($params['username']) || empty($params['password']) || empty($params['captcha'])){
            return $this->ajaxReturn(1, 'error', '参数缺失');
        }
        $baseAccount = config('app.adminAccount');
        $baseCaptcha = app('session')->get('milkcaptcha');
        if($baseCaptcha != md5($params['captcha'])){
            return $this->ajaxReturn(1, '验证码错误', '');
        }

        if(empty($baseAccount[$params['username']])){
            return $this->ajaxReturn(1, '账号或密码错误', '');
        }
        if($baseAccount[$params['username']] != $params['password']){
            return $this->ajaxReturn(1, '账号或密码错误', '');
        }

        $loginInfo = [
            'username'=>'admin',
            'loginTime'=> date('Y-m-d H:i:s'),
        ];

        app('session')->put('loginInfo', $loginInfo);

        return $this->ajaxReturn(0, '成功', ['url'=> route('admin.welcome')]);
    }

    public function logout(){
        app('session')->flush();

        return redirect()->route('login');
    }


    /**
     * 后台欢迎页面
     */
    public function welcome(){

        $proxySrv = new ProxySrv();
        $pingStatus = $proxySrv->pingStatus();
        $viewData = [
            'pingStatus'=>$pingStatus
        ];

        return view('admin.welcome', $viewData);
    }


    public function captcha(){

        $builder = new CaptchaBuilder(4);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        app('session')->put('milkcaptcha', md5($phrase));
        $builder->output();
        return response('')->header('Cache-Control','no-cache, must-revalidate')->header('Content-Type', 'image/jpeg');
    }

}
