<?php

namespace App\Restrictions;

trait JobState
{

    public function getHidden()
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
	    		return [];
	    	}
	        return [];
    	}
    	return [];
    }

    public function getGuarded()
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
	    		return [];
	    	}
	        return [];
    	}
    	return [];
    }

}
