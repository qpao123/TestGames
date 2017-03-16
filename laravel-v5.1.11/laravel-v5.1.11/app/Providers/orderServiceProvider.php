<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OrderService;

use App\Facades\Order;

class orderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('order',function(){
            return new Order;
        });

//        $this->app->singleton('order',function(){
//            return new OrderService();
//        });

        $this->app->bind('App\Contracts\OrderContract',function(){
            return new OrderService();
        });
    }
}
