<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Experience
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
