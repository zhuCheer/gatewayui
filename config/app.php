<?php

return [
    'name' => env('APP_NAME', 'Lumen'),

    'adminAccount'=>[
        'admin'=>'123456'
    ],

    'proxyServer'=>[
        'urlPre'=>'http://192.168.137.1:5001',
        'apis'=>[
            'regist' => '/api/regist',
            'insertone' => '/api/insertone',
            'removenoe' => '/api/removenoe',
            'reloadone' => '/api/reloadone',
            'flushone' => '/api/flushone',
            'ping' => '/api/ping',
        ]
    ]

];