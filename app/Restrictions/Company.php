<?php

namespace App\Restrictions;

trait Company
{

    public function getHidden()
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
	    		return [];
	    	}
	        return ['popularity_rating_id'];
    	}
    	return [];
    }

    public function getGuarded()
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
	    		return [];
	    	}
	        return ['popularity_rating_id'];
    	}
    	return [];
    }

    public function getPopularityRatingIdAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
	    		return $value;
	    	} else {
	        	return null;
	    	}
    	}
        return $value;
    }

    public function setPopularityRatingIdAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                $this->attributes['popularity_rating_id'] = $value;
	    	} else {

	    	}
    	}
        $this->attributes['popularity_rating_id'] = $value;
    }

}
