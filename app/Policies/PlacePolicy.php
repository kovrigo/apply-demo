<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Place;
use App\Contact;
use App\Workplace;

class PlacePolicy extends CustomizedPolicy
{

    public function addContact(User $user, Place $model)
    {
        return $this->policy->can('addContact', $model);
    }

    public function addWorkplace(User $user, Place $model)
    {
        return $this->policy->can('addWorkplace', $model);
    }

}

