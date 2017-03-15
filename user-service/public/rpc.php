<?php
use Paf\LightService\Server\Service;

$service = Service::create('jsonrpc', function($sModule, $sMethod, $aParams, $id) {
    $sTrackID     = array_get($aParams, '0.trackId', null);
    $sFrom        = array_get($aParams, '0.from', null);
    $iRequestTime = array_get($aParams, '0.requestTime', null);
    $sToken       = array_get($aParams, '0.token', null);
    $aParameters  = array_get($aParams, '1', []);

    //可以添加ip，token判断
    $sVersion = str_replace('.', '_', array_get($aParams, '0.version', ''));
    $sClass    = 'App\\Http\\Controllers' . "\\V{$sVersion}\\{$sModule}";

    if(!class_exists($sClass)){
        $aResponse = ['class is not exists -- '.$sClass];
    }else{
        $oClass    = new $sClass;
        $aResponse = call_user_func_array([$oClass, $sMethod], $aParameters);
    }

    return new \Paf\LightService\Server\Response($aResponse);
});

echo $service->respond(file_get_contents('php://input'));