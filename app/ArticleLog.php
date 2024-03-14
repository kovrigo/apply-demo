<?php

namespace App;

use App\Settings\Model;

class ArticleLog extends Model
{
	use \App\Relations\ArticleLog;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\ArticleLog;

}

