<?php

namespace App\Relations;

trait Transaction
{

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function tenant()
    {
        return $this->belongsTo('App\Tenant');
    }

}
