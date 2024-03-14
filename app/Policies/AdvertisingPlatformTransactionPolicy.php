<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\AdvertisingPlatformTransaction;

class AdvertisingPlatformTransactionPolicy extends CustomizedPolicy
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

}

