<?php

namespace App\Relations;

trait Skill
{

    public function jobSkillLevels()
    {
        return $this->hasMany('App\JobSkillLevel');
    }

    public function profileSkillLevels()
    {
        return $this->hasMany('App\ProfileSkillLevel');
    }

    public function jobs()
    {
        return $this->hasManyThrough('App\Job', 'App\JobSkillLevel');
    }

    public function profiles()
    {
        return $this->hasManyThrough('App\Profile', 'App\ProfileSkillLevel');
    }

}
