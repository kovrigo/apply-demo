<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Company;
use App\Contact;
use App\Place;
use App\PlaceGroup;
use App\Faq;
use App\Number;
use App\InfoBlock;

class CompanyPolicy extends CustomizedPolicy
{

    public function addContact(User $user, Company $model)
    {
        return $this->policy->can('addContact', $model);
    }

    public function addPlace(User $user, Company $model)
    {
        return $this->policy->can('addPlace', $model);
    }

    public function addPlaceGroup(User $user, Company $model)
    {
        return $this->policy->can('addPlaceGroup', $model);
    }

    public function addFaq(User $user, Company $model)
    {
        return $this->policy->can('addFaq', $model);
    }

    public function addNumber(User $user, Company $model)
    {
        return $this->policy->can('addNumber', $model);
    }

    public function addInfoBlock(User $user, Company $model)
    {
        return $this->policy->can('addInfoBlock', $model);
    }

}

