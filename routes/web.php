<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return redirect(route('login'));
});

$router->group(['middleware' => 'session'], function () use ($router) {

    $router->get('/login', [
        'as' => 'login', 'uses' => 'Home@login'
    ]);
    $router->post('/login', ['as' => 'loginDo', 'uses' => 'Home@loginDo']);
    $router->get('/logout', ['as' => 'logout', 'uses' => 'Home@logout']);


    $router->get('/captcha', [
        'as' => 'captcha', 'uses' => 'Home@captcha'
    ]);

    $router->group(['prefix' => 'admin', 'middleware'=>'login'], function () use ($router) {
        $router->get('/welcome', [
            'as' => 'admin.welcome', 'uses' => 'Home@welcome'
        ]);

        $router->get('/site_list', [
            'as' => 'admin.siteList', 'uses' => 'Admin@siteList'
        ]);

        $router->post('/site_info', [
            'as' => 'admin.siteInfo', 'uses' => 'Admin@siteInfo'
        ]);


        $router->get('/nodes', [
            'as' => 'admin.nodes', 'uses' => 'Admin@setNodes'
        ]);


        $router->group(['middleware'=>'proxyStatus'], function () use ($router) {
            $router->post('/site_save', [
                'as' => 'admin.siteSave', 'uses' => 'Admin@siteSave'
            ]);
            $router->post('/site_del', [
                'as' => 'admin.siteRemove', 'uses' => 'Admin@siteRemove'
            ]);

            $router->post('/node_save', [
                'as' => 'admin.saveNode', 'uses' => 'Admin@saveNode'
            ]);

            $router->post('/node_del', [
                'as' => 'admin.delNode', 'uses' => 'Admin@delNode'
            ]);

        });


    });



});
