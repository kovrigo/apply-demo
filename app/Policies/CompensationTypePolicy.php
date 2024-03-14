<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\CompensationType;

class CompensationTypePolicy extends CustomizedPolicy
{

    public function create(User $user)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                return parent::create($user);
	    	} else {
                return false;
	    	}
    	}
        return parent::create($user);
    }

    public function update(User $user, $model)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                return parent::update($user, $model);
	    	} else {
                return false;
	    	}
    	}
    	return parent::update($user, $model);
    }

    public function delete(User $user, $model)
    {
    	if (apply()->authenticated) {
	    	if (apply()->isHost) {
                return parent::delete($user, $model);
	    	} else {
                return false;
	    	}
    	}
    	return parent::delete($user, $model);
    }

}

