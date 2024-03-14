<?php

namespace App\Restrictions;

trait User
{

    public function getHidden()
    {
    	return [];
    }

    public function getGuarded()
    {
    	return [];
    }

}
