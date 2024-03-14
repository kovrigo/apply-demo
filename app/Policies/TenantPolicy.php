<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Tenant;
use App\UserProfile;
use App\Transaction;
use App\Faq;
use App\Number;
use App\InfoBlock;

class TenantPolicy extends CustomizedPolicy
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

    public function addUser(User $user, Tenant $model)
    {
        return $this->policy->can('addUser', $model);
    }

    public function addUserProfile(User $user, Tenant $model)
    {
        return $this->policy->can('addUserProfile', $model);
    }

    public function addTransaction(User $user, Tenant $model)
    {
        return $this->policy->can('addTransaction', $model);
    }

    public function addFaq(User $user, Tenant $model)
    {
        return $this->policy->can('addFaq', $model);
    }

    public function addNumber(User $user, Tenant $model)
    {
        return $this->policy->can('addNumber', $model);
    }

    public function addInfoBlock(User $user, Tenant $model)
    {
        return $this->policy->can('addInfoBlock', $model);
    }

}

