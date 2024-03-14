<?php

namespace App\Relations;

trait Creative
{

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function trigger()
    {
        return $this->belongsTo('App\Trigger');
    }

    public function callToActionType()
    {
        return $this->belongsTo('App\CallToActionType');
    }

    public function ads()
    {
        return $this->hasMany('App\Ad');
    }

    public function creativeState()
    {
        return $this->belongsTo('App\CreativeState');
    }

    public function creativeLogs()
    {
        return $this->hasMany('App\CreativeLog');
    }

}
