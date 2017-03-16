<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TestService;
use App\Facades\Test;

class TestServiceProvider extends ServiceProvider
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
     * @author LaravelAcademy.org
     */
    public function register()
    {
        //ʹ��singleton�󶨵���
        $this->app->singleton('test',function(){
            //return new TestService();
			return new Test();
		});

        //ʹ��bind��ʵ�����ӿ��Ա�����ע��
        $this->app->bind('App\Contracts\TestContract',function(){
            return new TestService();
        });
    }
}