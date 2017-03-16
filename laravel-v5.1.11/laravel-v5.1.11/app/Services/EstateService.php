<?php
/**
 * Created by PhpStorm.
 * User: qiaokeer
 * Date: 2016/3/15
 * Time: 15:50
 */
namespace App\Services;

abstract class EstateService
{
    protected $sAbstract;
    protected $sName;
    protected $aParams;
    protected $aResult;
    protected $iParamsCount;
    protected $oErrorHandler;

    public function __construct($sName, $aParams)
    {
        $this->sName        = $sName;
        $this->aParams      = $aParams;
        $this->iParamsCount = count($aParams);
    }

    public static function __callStatic($sName, $aParams)
    {
        return new static($sName, $aParams);
    }

    public function setErrorHandler($oErrorHandler)
    {
        $this->oErrorHandler = $oErrorHandler;
        return $this;
    }

    public function page($iPage)
    {
        if ($this->iParamsCount > 0) {
            if (is_array($this->aParams[$this->iParamsCount - 1])) {
                $this->aParams[$this->iParamsCount - 1]['iPage'] = $iPage;
            } else {
                array_push($this->aParams, ['iPage' => 1]);
                $this->iParamsCount++;
            }
        } else {
            $this->aParams[0]['iPage'] = $iPage;
        }
        return array_get($this->get(), 'aList', []);
    }

    public function get()
    {
        if (is_callable($this->oErrorHandler)) {
            $this->aResult = call_user_func_array(
                [app($this->sAbstract)->setErrorHandler($this->oErrorHandler), $this->sName],
                $this->aParams
            );
        } else {
            $this->aResult = call_user_func_array(
                [app($this->sAbstract), $this->sName],
                $this->aParams
            );
        }
        return $this->aResult;
    }

    public function all()
    {
        $iPage    = 1;
        $aResults = [];
        do {
            $aResult  = $this->page($iPage);
            $iTo      = array_get($this->aResult, 'iTo', 0);
            $iTotal   = $this->total();
            $aResults = array_merge($aResults, $aResult);
            $iPage++;
        } while ($iTo < $iTotal);
        return $aResults;
    }

    public function total()
    {
        return array_get($this->aResult, 'iTotal', 0);
    }

    public function lastPage()
    {
        return array_get($this->aResult, 'iLastPage', 1);
    }

    public function perPage()
    {
        return array_get($this->aResult, 'iPerPage', 1);
    }

    public function currentPage()
    {
        return array_get($this->aResult, 'iCurrentPage', 1);
    }

    public function from()
    {
        return array_get($this->aResult, 'iFrom', 1);
    }
    public function to()
    {
        return array_get($this->aResult, 'iTo', 1);
    }
}