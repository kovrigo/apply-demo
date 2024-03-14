<?php

namespace App\Relations;

trait NewOrderState
{

    public function newOrders()
    {
        return $this->hasMany('App\NewOrder');
    }

}

