<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Application;
use App\ApplicationLog;

class ApplicationPolicy extends CustomizedPolicy
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

    public function addApplicationLog(User $user, Application $model)
    {
        return $this->policy->can('addApplicationLog', $model);
    }

}

