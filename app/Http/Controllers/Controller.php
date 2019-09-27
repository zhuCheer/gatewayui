<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {

    }


    public function ajaxReturn($error, $info='', $data=[]){

        return response()->json(['status'=>$error, 'info'=>$info, 'data'=>$data]);
    }
}
