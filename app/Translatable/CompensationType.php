<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait CompensationType
{
	use HasTranslations;

	public $translatable = ['name'];

}
