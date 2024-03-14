<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Creative;
use App\Ad;
use App\CreativeLog;

class CreativePolicy extends CustomizedPolicy
{

    public function create(User $user)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                return false;
	    	} else {
                return parent::create($user);
	    	}
    	}
        return parent::create($user);
    }

    public function update(User $user, $model)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                return false;
	    	} else {
                return parent::update($user, $model);
	    	}
    	}
    	return parent::update($user, $model);
    }

    public function delete(User $user, $model)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                return false;
	    	} else {
                return parent::delete($user, $model);
	    	}
    	}
    	return parent::delete($user, $model);
    }

    public function addAd(User $user, Creative $model)
    {
        return $this->policy->can('addAd', $model);
    }

    public function addCreativeLog(User $user, Creative $model)
    {
        return $this->policy->can('addCreativeLog', $model);
    }

}

