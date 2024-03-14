<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;

use App\Settings\Model;
use Spatie\EloquentSortable\Sortable;

class Place extends Model implements HasMedia, Sortable
{
    use \App\Relations\Place;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\Place;
	use \App\Media\Place;
	use \App\Restrictions\Place;

    protected $casts = [
        'business_hours' => 'array',
    ];

    public static $resourceTitleAttribute = "default_title";

    public function getDefaultTitleAttribute()
    {
        if ($this->center_name) {
            return $this->center_name;
        }
        if ($this->address_line_1) {
            return $this->address_line_1;
        }
        if ($this->city) {
            return $this->city;
        }
    }

}

