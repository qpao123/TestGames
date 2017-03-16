<?php
namespace App\Services\Accessors;

use Log;

class ServiceAccessorWithRPC
{
    public $oRPCClient;

    protected $aRoute;
    protected $sFrom;
    protected $sKey;
    protected $sTo;

    public function __construct($oRPCClient, $aRoute, $sFrom, $sTo)
    {
        $this->oRPCClient = $oRPCClient;
        $this->aRoute = $aRoute;
        $this->sFrom = $sFrom;
        $this->sKey = 'zxzx123';
        $this->sTo = $sTo;
    }

    // ServiceAccessor需要实现6个方法

    // 执行一个请求
    public function request($sName, array $aParams)
    {
        $fStart = microtime(true);

        if (!isset($this->aRoute[$sName])) {
            $sName = strtolower($sName);
        }
        if (isset($this->aRoute[$sName])) {
            $sMethod = $this->aRoute[$sName]['sMethod'];
            $sModule = $this->aRoute[$sName]['sModule'];
            $sVersion = $this->aRoute[$sName]['sVersion'];
        } else {
            return "RPC ERROR: Route not found {$sName}";
        }

        $iRequestTime = time();
        $sToken = md5(sha1(json_encode($aParams)) . $this->sKey . $iRequestTime);
        $oModule = $this->oRPCClient->module($sModule);
        $mResponse = $oModule->{$sMethod}(['version'     => $sVersion,
                                           'from'        => $this->sFrom,
                                           'requestTime' => $iRequestTime,
                                           'token'       => $sToken,
                                           'trackId'     => $this->generateTrackID()
        ], $aParams);
        Log::debug("RPC REQUEST: {$this->sTo}:{$sName} (" . (microtime(true) - $fStart) . ')', $aParams);
        if ($mResponse) {
            return $mResponse;
        } else {
            return $oModule->errstr();
        }
    }

    public function isSuccess($mResponse)
    {
        return isset($mResponse['bSuccess']) && $mResponse['bSuccess'] === true;
    }

    public function getData($mResponse)
    {
        return $mResponse['aData'];
    }

    public function getError($mResponse)
    {
        Log::warning("RPC ERROR: ", is_array($mResponse) ? $mResponse : [$mResponse]);
        if (in_array(array_get($mResponse, 'sErrorType', false), ['LOGIC', 'VALIDATION'])) {
            return $mResponse['aErrors'];
        } else {
            return [];
        }
    }

    public function errorHandler($mError, &$mData = null)
    {

        // 处理错误, 这边可以进行一些善后工作, 然后抛出你的异常; 如果这里不抛出异常, 系统会为自动你抛出一个默认的异常

        // 第二个参数为一个引用, 当你在最后返回true时, 第二个参数的值会被作为接口的返回传出, 此时系统不会抛出异常而是以$mData作为正确返回继续执行下去
        if ($mError) {
            $mError = array_shift($mError);
            throw new \MobiException($mError['sMsg'], $mError['iCode']);
        } else {
            throw new \MobiException('SERVICE_ERROR');
        }
    }

    public function __call($sName, $aParams)
    {
        return $this->request($sName, $aParams);
    }

    public function __clone()
    {
        $this->oRPCClient = clone $this->oRPCClient;
    }

    //这个可以放到公共函数文件里面
    public function generateTrackID()
    {
        return strtoupper(preg_replace(
            '~^(.{8})(.{4})(.{4})(.{4})(.{12})$~',
            '\1-\2-\3-\4-\5',
            md5(uniqid('', true))
        ));
    }

}