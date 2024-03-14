<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;

use App\Settings\Model;

class Workplace extends Model implements HasMedia
{
    use \App\Relations\Workplace;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Media\Workplace;
	use \App\Restrictions\Workplace;

    public static $resourceTitleAttribute = "default_title";

    public function getDefaultTitleAttribute()
    {
        return implode(' \ ', [$this->addressable->company->model_title, 
            $this->addressable->model_title, $this->workplaceType->model_title]);
    }

}

