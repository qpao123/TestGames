<?php

namespace App\Services;

use App\Contracts\OrderContract;

class OrderService implements OrderContract
{
    public function fn($controller)
    {
        dd('Call Me From OrderServiceProvider In '.$controller);
    }
}