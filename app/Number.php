<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

use Spatie\EloquentSortable\Sortable;

class Number extends Model implements HasMedia, Sortable
{
    use \App\Relations\Number;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\Number;
	use \App\Media\Number;
	use \App\Restrictions\Number;

}

