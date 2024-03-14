<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

use Spatie\EloquentSortable\Sortable;

class InfoBlockItem extends Model implements HasMedia, Sortable
{
    use \App\Relations\InfoBlockItem;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\InfoBlockItem;
	use \App\Media\InfoBlockItem;
	use \App\Restrictions\InfoBlockItem;

}

