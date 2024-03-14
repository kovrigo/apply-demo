<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\PlaceGroup;
use App\Contact;
use App\PlaceGroupAddress;
use App\Workplace;

class PlaceGroupPolicy extends CustomizedPolicy
{

    public function addContact(User $user, PlaceGroup $model)
    {
        return $this->policy->can('addContact', $model);
    }

    public function addPlaceGroupAddress(User $user, PlaceGroup $model)
    {
        return $this->policy->can('addPlaceGroupAddress', $model);
    }

    public function addWorkplace(User $user, PlaceGroup $model)
    {
        return $this->policy->can('addWorkplace', $model);
    }

}

