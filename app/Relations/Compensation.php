<?php

namespace App\Relations;

trait Compensation
{

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function compensationType()
    {
        return $this->belongsTo('App\CompensationType');
    }

    public function compensationUnit()
    {
        return $this->belongsTo('App\CompensationUnit');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

}
