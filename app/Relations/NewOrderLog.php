<?php

namespace App\Relations;

trait NewOrderLog
{

    public function newOrderState()
    {
        return $this->belongsTo('App\NewOrderState');
    }

    public function newOrder()
    {
        return $this->belongsTo('App\NewOrder');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }    

    public function state()
    {
        return $this->newOrderState();
    }

}

