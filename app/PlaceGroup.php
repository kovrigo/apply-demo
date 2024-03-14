<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;

use App\Settings\Model;

class PlaceGroup extends Model implements HasMedia
{
    use \App\Relations\PlaceGroup;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Media\PlaceGroup;
	use \App\Restrictions\PlaceGroup;

}

