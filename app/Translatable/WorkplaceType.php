<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait WorkplaceType
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
