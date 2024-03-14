<?php

namespace App\Relations;

trait Article
{

    public function relatedArticles()
    {
        return $this->belongsToMany('App\Article', 'article_article', 'article_id', 'related_article_id');
    }

}

