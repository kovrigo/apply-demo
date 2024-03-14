<?php

namespace App\Relations;

trait JobSkillLevel
{

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill');
    }

    public function skillLevel()
    {
        return $this->belongsTo('App\SkillLevel');
    }

}
