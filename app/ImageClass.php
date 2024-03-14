<?php

namespace App;

use App\Settings\Model;

class ImageClass extends Model
{
    use \App\Relations\ImageClass;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\ImageClass;

}

