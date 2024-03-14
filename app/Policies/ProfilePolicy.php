<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Profile;
use App\Contact;
use App\Application;
use App\Experience;
use App\Employment;
use App\Degree;

class ProfilePolicy extends CustomizedPolicy
{

    public function addContact(User $user, Profile $model)
    {
        return $this->policy->can('addContact', $model);
    }

    public function addApplication(User $user, Profile $model)
    {
        return $this->policy->can('addApplication', $model);
    }

    public function attachExperience(User $user, Profile $model, Experience $relatedModel)
    {
        return $this->policy->can('attachExperience', $model, $relatedModel);
    }

    public function attachEmployment(User $user, Profile $model, Employment $relatedModel)
    {
        return $this->policy->can('attachEmployment', $model, $relatedModel);
    }

    public function attachDegree(User $user, Profile $model, Degree $relatedModel)
    {
        return $this->policy->can('attachDegree', $model, $relatedModel);
    }

}

