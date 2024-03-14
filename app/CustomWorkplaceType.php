<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class CustomWorkplaceType extends Model implements HasMedia
{
    use \App\Relations\CustomWorkplaceType;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Media\CustomWorkplaceType;
	use \App\Restrictions\CustomWorkplaceType;

}

