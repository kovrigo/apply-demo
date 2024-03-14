<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\Settings\Model;

class Creative extends Model implements HasMedia
{
    use \App\Relations\Creative;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Media\Creative;
    use \App\Workflows\Traits\Workflowable;
	use \App\Restrictions\Creative;
	use \App\Traits\RespectsOwnership;

}

