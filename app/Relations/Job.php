<?php

namespace App\Relations;

trait Job
{

    public function workplace()
    {
        return $this->belongsTo('App\Workplace');
    }

    public function benefits()
    {
        return $this->belongsToMany('App\Benefit');
    }

    public function functionalAreas()
    {
        return $this->belongsToMany('App\FunctionalArea');
    }

    public function customFunctionalAreas()
    {
        return $this->belongsToMany('App\CustomFunctionalArea');
    }

    public function jobSkillLevels()
    {
        return $this->hasMany('App\JobSkillLevel');
    }

    public function compensations()
    {
        return $this->hasMany('App\Compensation');
    }

    public function degrees()
    {
        return $this->morphToMany('App\Degree', 'degree_pivot');
    }

    public function degree()
    {
        return $this->belongsTo('App\Degree');
    }

    public function experiences()
    {
        return $this->morphToMany('App\Experience', 'experience_pivot');
    }

    public function experience()
    {
        return $this->belongsTo('App\Experience');
    }

    public function employments()
    {
        return $this->morphToMany('App\Employment', 'employment_pivot');
    }

    public function creatives()
    {
        return $this->hasMany('App\Creative');
    }

    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function creativeWorkflow()
    {
        return $this->belongsTo('App\CreativeWorkflow');
    }

    public function applicationWorkflow()
    {
        return $this->belongsTo('App\ApplicationWorkflow');
    }

    public function ads()
    {
        return $this->hasMany('App\Ad');
    }

    public function jobState()
    {
        return $this->belongsTo('App\JobState');
    }

    public function jobLogs()
    {
        return $this->hasMany('App\JobLog');
    }

    public function cities()
    {
        return $this->belongsToMany('App\City');
    }    

    public function jobPageTemplate()
    {
        return $this->belongsTo('App\JobPageTemplate');
    }

}

