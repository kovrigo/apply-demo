<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Job;
use App\Creative;
use App\Transaction;
use App\Application;
use App\Ad;
use App\FunctionalArea;
use App\Compensation;
use App\JobLog;
use App\CustomFunctionalArea;
use App\Benefit;
use App\Experience;
use App\Employment;
use App\Degree;

class JobPolicy extends CustomizedPolicy
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

    public function addCreative(User $user, Job $model)
    {
        return $this->policy->can('addCreative', $model);
    }

    public function addTransaction(User $user, Job $model)
    {
        return $this->policy->can('addTransaction', $model);
    }

    public function addApplication(User $user, Job $model)
    {
        return $this->policy->can('addApplication', $model);
    }

    public function addAd(User $user, Job $model)
    {
        return $this->policy->can('addAd', $model);
    }

    public function attachFunctionalArea(User $user, Job $model, FunctionalArea $relatedModel)
    {
        return $this->policy->can('attachFunctionalArea', $model, $relatedModel);
    }

    public function addCompensation(User $user, Job $model)
    {
        return $this->policy->can('addCompensation', $model);
    }

    public function addJobLog(User $user, Job $model)
    {
        return $this->policy->can('addJobLog', $model);
    }

    public function attachCustomFunctionalArea(User $user, Job $model, CustomFunctionalArea $relatedModel)
    {
        return $this->policy->can('attachCustomFunctionalArea', $model, $relatedModel);
    }

    public function attachBenefit(User $user, Job $model, Benefit $relatedModel)
    {
        return $this->policy->can('attachBenefit', $model, $relatedModel);
    }

    public function attachExperience(User $user, Job $model, Experience $relatedModel)
    {
        return $this->policy->can('attachExperience', $model, $relatedModel);
    }

    public function attachEmployment(User $user, Job $model, Employment $relatedModel)
    {
        return $this->policy->can('attachEmployment', $model, $relatedModel);
    }

    public function attachDegree(User $user, Job $model, Degree $relatedModel)
    {
        return $this->policy->can('attachDegree', $model, $relatedModel);
    }

    public function attachCity(User $user, Job $model, $relatedModel)
    {
        return $this->policy->can('attachCity', $model, $relatedModel);
    }

}

