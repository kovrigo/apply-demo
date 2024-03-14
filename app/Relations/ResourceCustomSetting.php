<?php

namespace App\Relations;

trait ResourceCustomSetting
{

    public function userProfile()
    {
        return $this->belongsTo('App\UserProfile');
    }

}
