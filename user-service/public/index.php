<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/
require 'rpc.php';

//use Paf\LightService\Server\Service;
//
//$service = Service::create('jsonrpc', function($sModule, $sMethod, $aParams, $id) {
//	$sTrackID     = array_get($aParams, '0.trackId', null);
//	$sFrom        = array_get($aParams, '0.from', null);
//	$iRequestTime = array_get($aParams, '0.requestTime', null);
//	$sToken       = array_get($aParams, '0.token', null);
//	$aParameters  = array_get($aParams, '1', []);
//
//	//可以添加ip，token判断
//	$sVersion = str_replace('.', '_', array_get($aParams, '0.version', ''));
//	$sClass    = 'App\\Http\\Controllers' . "\\V{$sVersion}\\{$sModule}";
//
//	if(!class_exists($sClass)){
//		$aResponse = ['class is not exists -- '.$sClass];
//	}else{
//		$oClass    = new $sClass;
//		$aResponse = call_user_func_array([$oClass, $sMethod], $aParameters);
//	}
//
//    return new \Paf\LightService\Server\Response($aResponse);
//});
//
//echo $service->respond(file_get_contents('php://input'));


// $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// $response = $kernel->handle(
    // $request = Illuminate\Http\Request::capture()
// );

// $response->send();

// $kernel->terminate($request, $response);
