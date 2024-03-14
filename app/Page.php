<?php

namespace App;

use App\Settings\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Page extends Model implements HasMedia
{
    use \App\Relations\Page;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Media\Page;
	use \App\Restrictions\Page;

}

