<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TestClass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'test';
    }
}