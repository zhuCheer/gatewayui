<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    public function boot()
    {
        $siteMenus = config('menus');
        view()->share('siteMenus', $siteMenus);
        view()->share('adminStatic', '/static/flatlab');
        view()->share('siteStatic', '/static');
    }


}
