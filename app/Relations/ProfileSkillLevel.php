<?php

namespace App\Relations;

trait ProfileSkillLevel
{

    public function profile()
    {
        return $this->belongsTo('App\Profile');
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
