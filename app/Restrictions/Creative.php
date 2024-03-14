<?php

namespace App\Restrictions;

trait Creative
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
	    		return ['advertising_platform_responses'];
	    	}
	        return ['advertising_platform_responses'];
    	}
    	return [];
    }

    public function getAdvertisingPlatformResponsesAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
	    		return $value;
	    	} else {
	        	return $value;
	    	}
    	}
        return $value;
    }

    public function setAdvertisingPlatformResponsesAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {

	    	} else {

	    	}
    	}
        $this->attributes['advertising_platform_responses'] = $value;
    }

}
