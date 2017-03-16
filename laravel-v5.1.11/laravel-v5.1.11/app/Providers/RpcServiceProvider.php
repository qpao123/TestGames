<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RpcServiceProvider extends ServiceProvider
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
        foreach (config('rpc.client') as $sServiceName => $aConfig) {
            \Paf\LightService\Client\Service::importConf([$sServiceName => $aConfig]);

            $this->app->singleton("service.rpc.{$sServiceName}", function ($oApp) use ($sServiceName, $aConfig) {
                return new $aConfig['accessor'](\Paf\LightService\Client\Service::get($sServiceName), $aConfig['route'], config('rpc.from'), $sServiceName);
            });
        }
    }
}
