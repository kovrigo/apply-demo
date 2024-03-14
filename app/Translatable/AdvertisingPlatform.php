<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait AdvertisingPlatform
{
	use HasTranslations;

	public $translatable = ['name'];

}
