<?php

namespace App\Relations;

trait Profile
{

    public function applications()
    {
        return $this->hasMany('App\Application');
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

    public function contacts()
    {
        return $this->morphMany('App\Contact', 'contactable');
    }

    public function profileSkillLevels()
    {
        return $this->hasMany('App\ProfileSkillLevel');
    }

}
