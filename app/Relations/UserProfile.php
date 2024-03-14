<?php

namespace App\Relations;

trait UserProfile
{

    public function resourceCustomSettings()
    {
        return $this->hasMany('App\ResourceCustomSetting');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')
            ->using('App\UserUserProfile');
    }

    public function jobWorkflows()
    {
        return $this->belongsToMany('App\JobWorkflow');
    }

    public function creativeWorkflows()
    {
        return $this->belongsToMany('App\CreativeWorkflow');
    }

    public function applicationWorkflows()
    {
        return $this->belongsToMany('App\ApplicationWorkflow');
    }    

    public function chatWorkflows()
    {
        return $this->belongsToMany('App\ChatWorkflow');
    }

    public function chatMessageWorkflows()
    {
        return $this->belongsToMany('App\ChatMessageWorkflow');
    }

    public function articleWorkflows()
    {
        return $this->belongsToMany('App\ArticleWorkflow');
    }

    public function projectWorkflows()
    {
        return $this->belongsToMany('App\ProjectWorkflow');
    }             

    public function newOrderWorkflows()
    {
        return $this->belongsToMany('App\NewOrderWorkflow');
    }    

}

