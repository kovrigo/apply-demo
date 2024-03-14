<?php

namespace App;

use App\Settings\Model;

class ArticleState extends Model
{
	use \App\Relations\ArticleState;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Translatable\ArticleState;
	use \App\Restrictions\ArticleState;

}

