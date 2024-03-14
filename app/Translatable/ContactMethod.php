<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait ContactMethod
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
