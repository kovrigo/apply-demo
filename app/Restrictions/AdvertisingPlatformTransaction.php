<?php

namespace App\Restrictions;

trait AdvertisingPlatformTransaction
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
	    		return ['advertising_platform_id', 'amount', 'currency_id'];
	    	}
	        return [];
    	}
    	return [];
    }

    public function getAdvertisingPlatformIdAttribute($value)
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

    public function setAdvertisingPlatformIdAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {

	    	} else {
                $this->attributes['advertising_platform_id'] = $value;
	    	}
    	}
        $this->attributes['advertising_platform_id'] = $value;
    }

	    public function getAmountAttribute($value)
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

    public function setAmountAttribute($value)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {

	    	} else {
                $this->attributes['amount'] = $value;
	    	}
    	}
        $this->attributes['amount'] = $value;
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

	    	} else {
                $this->attributes['currency_id'] = $value;
	    	}
    	}
        $this->attributes['currency_id'] = $value;
    }

}
