<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class ArtistPolicy extends CustomizedPolicy
{

    public function addComment(User $user, $model)
    {
        return $this->policy->can('addComment', $model);
    }

    public function attachRecord(User $user, Artist $model, $relatedModel)
    {
        return $this->policy->can('attachRecord', $model, $relatedModel);
    }

}

