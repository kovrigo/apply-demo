<?php

namespace App;

use App\ApplicationStageType;
use App\Settings\Model;

use Spatie\MediaLibrary\HasMedia\HasMedia;

class ApplicationStage extends Model implements HasMedia
{
    use \App\Relations\ApplicationStage;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Translatable\ApplicationStage;
	use \App\Media\ApplicationStage;
	use \App\Restrictions\ApplicationStage;

}

