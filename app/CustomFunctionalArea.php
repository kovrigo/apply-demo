<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\EloquentSortable\Sortable;

class CustomFunctionalArea extends Model implements HasMedia, Sortable
{
    use \App\Relations\CustomFunctionalArea;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\CustomFunctionalArea;
	use \App\Media\CustomFunctionalArea;
	use \App\Restrictions\CustomFunctionalArea;

    public function getCustomFunctionalAreaGroupTitleAttribute()
    {
        return $this->customFunctionalAreaGroup->model_title;
    }

}

