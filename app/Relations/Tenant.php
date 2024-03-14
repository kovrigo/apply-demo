<?php

namespace App\Relations;

trait Tenant
{

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function systemUser()
    {
        return $this->belongsTo('App\User', 'system_user_id');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

    public function userProfiles()
    {
        return $this->hasMany('App\UserProfile');
    }

    public function resourceCustomSettings()
    {
        return $this->hasMany('App\ResourceCustomSetting');
    }

    public function applicationStages()
    {
        return $this->hasMany('App\ApplicationStage');
    }

    public function profiles()
    {
        return $this->hasMany('App\Profile');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function faqs()
    {
        return $this->morphMany('App\Faq', 'relatable');
    }

    public function numbers()
    {
        return $this->morphMany('App\Number', 'relatable');
    }

    public function infoBlocks()
    {
        return $this->morphMany('App\InfoBlock', 'relatable');
    }

    public function tenant()
    {
        return $this;
    }

}
