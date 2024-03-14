<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

use Spatie\EloquentSortable\Sortable;

class CustomFunctionalAreaGroup extends Model implements HasMedia, Sortable
{
    use \App\Relations\CustomFunctionalAreaGroup;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\CustomFunctionalAreaGroup;
	use \App\Media\CustomFunctionalAreaGroup;
	use \App\Restrictions\CustomFunctionalAreaGroup;

}

