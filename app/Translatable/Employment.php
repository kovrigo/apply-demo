<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Employment
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
