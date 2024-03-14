<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

use Spatie\EloquentSortable\Sortable;

class InfoBlock extends Model implements HasMedia, Sortable
{
    use \App\Relations\InfoBlock;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\InfoBlock;
	use \App\Media\InfoBlock;
	use \App\Restrictions\InfoBlock;

}

