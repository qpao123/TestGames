<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OrderClass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'order';
    }
}