<?php

namespace App\Restrictions;

trait Tenant
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
	        return ['name', 'currency_id', 'career_website_url', 'career_website_integration'];
    	}
    	return [];
    }

    public function getNameAttribute($value)
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

    public function setNameAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                $this->attributes['name'] = $value;
	    	} else {

	    	}
    	}
        $this->attributes['name'] = $value;
    }

	    public function getCurrencyIdAttribute($value)
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

    public function setCurrencyIdAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                $this->attributes['currency_id'] = $value;
	    	} else {

	    	}
    	}
        $this->attributes['currency_id'] = $value;
    }

	    public function getCareerWebsiteUrlAttribute($value)
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

    public function setCareerWebsiteUrlAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                $this->attributes['career_website_url'] = $value;
	    	} else {

	    	}
    	}
        $this->attributes['career_website_url'] = $value;
    }

	    public function getCareerWebsiteIntegrationAttribute($value)
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

    public function setCareerWebsiteIntegrationAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                $this->attributes['career_website_integration'] = $value;
	    	} else {

	    	}
    	}
        $this->attributes['career_website_integration'] = $value;
    }

}
