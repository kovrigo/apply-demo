<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Article;

class ArticlePolicy extends CustomizedPolicy
{

    public function addArticleLog(User $user, $model)
    {
        return $this->policy->can('addArticleLog', $model);
    }

    public function attachArticle(User $user, Article $model, $relatedModel)
    {
        return $this->policy->can('attachArticle', $model, $relatedModel);
    }

}

