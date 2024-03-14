<?php

namespace App\Relations;

trait CreativeLog
{

    public function creativeState()
    {
        return $this->belongsTo('App\CreativeState');
    }

    public function creative()
    {
        return $this->belongsTo('App\Creative');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function state()
    {
        return $this->creativeState();
    }

}
