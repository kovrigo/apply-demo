<?php

namespace App\Relations;

trait ProjectTransaction
{

    public function owner()
    {
        return $this->morphTo()->withTrashed();
    }    

    public function projectTransaction()
    {
        return $this->belongsTo('App\ProjectTransaction');
    }

    public function projectTransactions()
    {
        return $this->hasMany('App\ProjectTransaction');
    }    

}

