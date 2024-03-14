<?php

namespace App;

use App\Settings\Model;

class Comment extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Relations\Comment;
	use \App\Restrictions\Comment;

}

