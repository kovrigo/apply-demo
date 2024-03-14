<?php

namespace App;

use App\Settings\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Article extends Model implements Sortable, HasMedia
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Workflows\Traits\Workflowable;
	use \App\Traits\RespectsOwnership;
	use \App\Sortable\Article;
	use \App\Media\Article;
	use \App\Relations\Article;
	use \App\Restrictions\Article;

}

