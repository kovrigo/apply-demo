<?php

namespace App\Relations;

trait SkillLevel
{

    public function jobSkillLevels()
    {
        return $this->hasMany('App\JobSkillLevel');
    }

    public function profileSkillLevels()
    {
        return $this->hasMany('App\ProfileSkillLevel');
    }

}
