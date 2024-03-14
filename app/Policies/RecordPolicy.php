<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class RecordPolicy extends CustomizedPolicy
{

    public function addRecord(User $user, $model)
    {
        return $this->policy->can('addRecord', $model);
    }

    public function addComment(User $user, $model)
    {
        return $this->policy->can('addComment', $model);
    }

    public function attachArtist(User $user, Record $model, $relatedModel)
    {
        return $this->policy->can('attachArtist', $model, $relatedModel);
    }

}

